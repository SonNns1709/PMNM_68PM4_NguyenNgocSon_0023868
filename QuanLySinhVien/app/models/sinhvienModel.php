<?php
require_once '../app/core/Model.php';

class SinhvienModel extends Model
{
    // Đã xóa hàm __construct dư thừa để sửa lỗi sonarqube(php:S1185)
    private function parseDuplicateError(Exception $e): string
    {
        // Message PDO dạng: ...Duplicate entry 'SV002' for key 'mssv'
        if (preg_match("/Duplicate entry '(.+)' for key '(.+)'/", $e->getMessage(), $m)) {
            $value = $m[1];
            $key   = $m[2];

            $labels = [
                'mssv'               => 'Mã số sinh viên (MSSV)',
                'tbl_sinhviens.mssv' => 'Mã số sinh viên (MSSV)',
                'ma_lop'             => 'Mã lớp',
            ];
            $label = $labels[$key] ?? $key;

            return "$label '$value' đã tồn tại trong hệ thống! Vui lòng nhập giá trị khác.";
        }
        return 'Dữ liệu bị trùng, vui lòng kiểm tra lại các trường đã nhập!';
    }

    /**
     * Lấy toàn bộ sinh viên
     * Định nghĩa kiểu trả về là một mảng (array)
     */
    public function getAllSinhvien(): array
    {
        $query  = "SELECT * FROM tbl_sinhviens ORDER BY id ASC";
        $stmt   = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllLop()
    {
        $stmt = $this->conn->query(
            "SELECT * FROM tbl_lops
             ORDER BY nien_khoa DESC, ma_nganh ASC, stt ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm sinh viên mới
     * Thêm kiểu dữ liệu string cho các tham số và kiểu bool cho kết quả trả về
     */
    public function create(
        string $hoten,
        string $gioitinh,
        string $mssv,
        string $nganh,
        ?int $lopId,
        string $ghiChu,
        array $diems = []
    ): array {
        try {
            $query = "INSERT INTO tbl_sinhviens(hoten, gioitinh, mssv, nganh, lop_id, ghi_chu)
                      VALUES(:hoten, :gioitinh, :mssv, :nganh, :lop_id, :ghi_chu)";
            $stmt  = $this->conn->prepare($query);
            $stmt->bindParam(':hoten',    $hoten);
            $stmt->bindParam(':gioitinh', $gioitinh);
            $stmt->bindParam(':mssv',     $mssv);
            $stmt->bindParam(':nganh',    $nganh);
            $stmt->bindValue(':lop_id',   $lopId ?: null, PDO::PARAM_INT);
            $stmt->bindParam(':ghi_chu',  $ghiChu);
            $stmt->execute();

            $svId = $this->conn->lastInsertId();

            if (!empty($diems)) {
                foreach ($diems as $monhocId => $d) {
                    $cc = (float)($d['cc'] ?? 0);
                    $gk = (float)($d['gk'] ?? 0);
                    $ck = (float)($d['ck'] ?? 0);
                    $tk = round($cc * 0.1 + $gk * 0.3 + $ck * 0.6, 2);

                    $qD = "INSERT INTO tbl_diems
                               (sinhvien_id, monhoc_id,
                                diem_chuyen_can, diem_giua_ky,
                                diem_cuoi_ky, diem_tong_ket)
                           VALUES(:sv, :mh, :cc, :gk, :ck, :tk)";
                    $sD = $this->conn->prepare($qD);
                    $sD->bindValue(':sv', $svId,     PDO::PARAM_INT);
                    $sD->bindValue(':mh', $monhocId, PDO::PARAM_INT);
                    $sD->bindValue(':cc', $cc);
                    $sD->bindValue(':gk', $gk);
                    $sD->bindValue(':ck', $ck);
                    $sD->bindValue(':tk', $tk);
                    $sD->execute();
                }
            }
            return ['success' => true];

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return ['success' => false, 'error' => $this->parseDuplicateError($e)];
            }
            return ['success' => false, 'error' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    /**
     * Phân trang, tìm kiếm và lọc dữ liệu sinh viên nâng cao
     * FIX 1: Thêm tham số $lop nhận giá trị lọc lớp từ bộ lọc điều hướng
     * FIX 2: Bổ sung LEFT JOIN tbl_lops l để lấy thông tin hiển thị cột Lớp học
     */
    public function paging($limit = 5, $offset = 0, string $search = "", string $xepLoai = "", string $nganh = "", string $lop = ""): array
    {
        // Subquery tính GPA có trọng số tín chỉ
        $gpaSub = "(SELECT ROUND(
                        SUM(d2.diem_tong_ket * m2.so_tin_chi) / SUM(m2.so_tin_chi), 2
                    )
                    FROM tbl_diems d2
                    LEFT JOIN tbl_monhocs m2 ON d2.monhoc_id = m2.id
                    WHERE d2.sinhvien_id = sv.id)";

        // Xây dựng điều kiện WHERE
        $conditions = [];
        $bindParams = [];

        if (!empty($search)) {
            $conditions[] = "(sv.hoten LIKE :search OR sv.mssv LIKE :search)";
            $bindParams[':search'] = '%' . $search . '%';
        }

        if (!empty($xepLoai)) {
            $gpaCond = $gpaSub;
            $xepLoaiMap = [
                'xuat_sac'   => "$gpaCond >= 8.5",
                'gioi'       => "$gpaCond >= 7.0 AND $gpaCond < 8.5",
                'kha'        => "$gpaCond >= 5.5 AND $gpaCond < 7.0",
                'trung_binh' => "$gpaCond >= 4.0 AND $gpaCond < 5.5",
                'yeu'        => "$gpaCond < 4.0",
            ];
            if (isset($xepLoaiMap[$xepLoai])) {
                $conditions[] = $xepLoaiMap[$xepLoai];
            }
        }

        if (!empty($nganh)) {
            $conditions[] = "sv.nganh = :nganh";
            $bindParams[':nganh'] = $nganh;
        }

        // FIX LỖI BỘ LỌC: Nếu có chọn lớp cụ thể, lọc theo ID của lớp
        if (!empty($lop)) {
            $conditions[] = "sv.lop_id = :lop";
            $bindParams[':lop'] = (int)$lop;
        }

        $where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        // FIX LỖI HIỂN THỊ DANH SÁCH: Thực hiện LEFT JOIN để lấy thông tin ma_lop và nganh (lop_nganh) từ bảng lớp
        $query = "SELECT sv.*, $gpaSub AS gpa, l.ma_lop, l.nganh AS lop_nganh
                  FROM tbl_sinhviens sv
                  LEFT JOIN tbl_lops l ON sv.lop_id = l.id
                  $where
                  ORDER BY sv.id ASC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        foreach ($bindParams as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Đếm tổng bản ghi tương ứng bộ lọc
        $countStmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM tbl_sinhviens sv $where"
        );
        foreach ($bindParams as $key => $val) {
            $countStmt->bindValue($key, $val);
        }
        $countStmt->execute();
        $totalRecord = $countStmt->fetchColumn();

        return [
            "sinhviens" => $result,
            "totalpage" => $totalRecord > 0 ? ceil($totalRecord / $limit) : 1
        ];
    }

    /**
     * Lấy 1 sinh viên theo ID (dùng để pre-fill form Sửa)
     * FIX: Ép kiểu int cho $id và trả về array hoặc false (Intelephense)
     */
    public function getById(int $id)
    {
        $query = "SELECT * FROM tbl_sinhviens WHERE id = :id LIMIT 1";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật sinh viên
     * FIX 1: Thêm type hinting (int, string) cho toàn bộ tham số (Intelephense)
     * FIX 2: Viết gọn câu lệnh return trực tiếp $stmt->execute() (Sửa lỗi sonarqube php:S1126)
     */
    public function update(
        int $id,
        string $hoten,
        string $gioitinh,
        string $mssv,
        string $nganh,
        ?int $lopId,
        string $ghiChu,
        array $diems = []
    ): array {
        try {
            $query = "UPDATE tbl_sinhviens
                      SET hoten=:hoten, gioitinh=:gioitinh, mssv=:mssv,
                          nganh=:nganh, lop_id=:lop_id, ghi_chu=:ghi_chu
                      WHERE id=:id";
            $stmt  = $this->conn->prepare($query);
            $stmt->bindParam(':hoten',    $hoten);
            $stmt->bindParam(':gioitinh', $gioitinh);
            $stmt->bindParam(':mssv',     $mssv);
            $stmt->bindParam(':nganh',    $nganh);
            $stmt->bindValue(':lop_id',   $lopId ?: null, PDO::PARAM_INT);
            $stmt->bindParam(':ghi_chu',  $ghiChu);
            $stmt->bindParam(':id',       $id, PDO::PARAM_INT);
            $stmt->execute();

            if (!empty($diems)) {
                foreach ($diems as $monhocId => $d) {
                    $cc = (float)($d['cc'] ?? 0);
                    $gk = (float)($d['gk'] ?? 0);
                    $ck = (float)($d['ck'] ?? 0);
                    $tk = round($cc * 0.1 + $gk * 0.3 + $ck * 0.6, 2);

                    $qU = "INSERT INTO tbl_diems
                               (sinhvien_id, monhoc_id,
                                diem_chuyen_can, diem_giua_ky,
                                diem_cuoi_ky, diem_tong_ket)
                           VALUES(:sv, :mh, :cc, :gk, :ck, :tk)
                           ON DUPLICATE KEY UPDATE
                               diem_chuyen_can = VALUES(diem_chuyen_can),
                               diem_giua_ky    = VALUES(diem_giua_ky),
                               diem_cuoi_ky    = VALUES(diem_cuoi_ky),
                               diem_tong_ket   = VALUES(diem_tong_ket)";
                    $sU = $this->conn->prepare($qU);
                    $sU->bindValue(':sv', $id,       PDO::PARAM_INT);
                    $sU->bindValue(':mh', $monhocId, PDO::PARAM_INT);
                    $sU->bindValue(':cc', $cc);
                    $sU->bindValue(':gk', $gk);
                    $sU->bindValue(':ck', $ck);
                    $sU->bindValue(':tk', $tk);
                    $sU->execute();
                }
            }
            return ['success' => true];

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return ['success' => false, 'error' => $this->parseDuplicateError($e)];
            }
            return ['success' => false, 'error' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    // ── Xóa sinh viên ─────────────────────────────────────────────
    public function delete(int $id): bool
    {
        $query = "DELETE FROM tbl_sinhviens WHERE id = :id";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getBangDiemBySinhvienId(int $sinhvien_id)
    {
        $query = "SELECT d.*, m.ma_mon, m.ten_mon, m.so_tin_chi
                  FROM tbl_diems d
                  JOIN tbl_monhocs m ON d.monhoc_id = m.id
                  WHERE d.sinhvien_id = :id
                  ORDER BY m.ma_mon ASC";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id', $sinhvien_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllMonhocs()
    {
        $stmt = $this->conn->query(
            "SELECT * FROM tbl_monhocs ORDER BY ma_mon ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllNganh()
    {
        $stmt = $this->conn->query(
            "SELECT DISTINCT nganh FROM tbl_sinhviens
             WHERE nganh != '' AND nganh != 'Chưa xác định'
             ORDER BY nganh ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
