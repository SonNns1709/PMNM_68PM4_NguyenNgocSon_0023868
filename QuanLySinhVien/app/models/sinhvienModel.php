<?php
require_once '../app/core/Model.php';

class SinhvienModel extends Model
{
    // Đã xóa hàm __construct dư thừa để sửa lỗi sonarqube(php:S1185)

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

    /**
     * Thêm sinh viên mới
     * Thêm kiểu dữ liệu string cho các tham số và kiểu bool cho kết quả trả về
     */
    public function create(string $hoten, string $gioitinh, string $mssv): bool
    {
        $query = "INSERT INTO tbl_sinhviens(hoten, gioitinh, mssv)
                  VALUES(:hoten, :gioitinh, :mssv)";
        $stmt  = $this->conn->prepare($query);
        
        $stmt->bindParam(':hoten',    $hoten);
        $stmt->bindParam(':gioitinh', $gioitinh);
        $stmt->bindParam(':mssv',     $mssv);

        // Đã sửa thành một câu lệnh return duy nhất để loại bỏ lỗi sonarqube(php:S1126)
        return $stmt->execute();
    }

       public function paging($limit = 5, $offset = 0, $search = "")
    {
        // ── 1. Truy vấn có search ──────────────────────────────────
        if (!empty($search)) {
            $keyword = '%' . $search . '%';

            $query = "SELECT * FROM tbl_sinhviens
                      WHERE hoten LIKE :search OR mssv LIKE :search
                      LIMIT :limit OFFSET :offset";
            $stmt  = $this->conn->prepare($query);
            $stmt->bindParam(':search', $keyword);
            $stmt->bindParam(':limit',  $limit,  PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Đếm tổng bản ghi phù hợp (để tính đúng tổng trang)
            $countStmt = $this->conn->prepare(
                "SELECT COUNT(*) FROM tbl_sinhviens
                 WHERE hoten LIKE :search OR mssv LIKE :search"
            );
            $countStmt->bindParam(':search', $keyword);
            $countStmt->execute();
            $totalRecord = $countStmt->fetchColumn();

        } else {
            // ── 2. Truy vấn không search (đúng như ảnh Thầy) ──────
            $query = "SELECT * FROM tbl_sinhviens
                      LIMIT :limit OFFSET :offset";
            $stmt  = $this->conn->prepare($query);
            $stmt->bindParam(':limit',  $limit,  PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Lấy tổng số bản ghi để tính tổng số trang
            $selectAllQuery = $this->conn->query(
                "SELECT COUNT(*) FROM tbl_sinhviens"
            );
            $totalRecord = $selectAllQuery->fetchColumn();
        }

        // ── 3. Tính tổng trang ─────────────────────────────────────
        $totalPage = ceil($totalRecord / $limit);

        return [
            "sinhviens" => $result,
            "totalpage" => $totalPage
        ];
    }
}
