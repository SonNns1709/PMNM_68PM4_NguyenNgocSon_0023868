<?php
require_once '../app/core/Model.php';

class ThongkeModel extends Model
{
    // GPA subquery dùng chung
    private $gpaSub = "(SELECT ROUND(SUM(d.diem_tong_ket*m.so_tin_chi)/SUM(m.so_tin_chi),2)
                        FROM tbl_diems d JOIN tbl_monhocs m ON d.monhoc_id=m.id
                        WHERE d.sinhvien_id=sv.id)";

    // ── Tổng quan ──────────────────────────────────────────────
    public function getTongQuan()
    {
        $totalSV = $this->conn
            ->query("SELECT COUNT(*) FROM tbl_sinhviens")
            ->fetchColumn();

        $gpaSub = $this->gpaSub;
        $row = $this->conn->query(
            "SELECT MAX(gpa) AS max_gpa,
                    MIN(gpa) AS min_gpa,
                    ROUND(AVG(gpa),2) AS avg_gpa
             FROM (SELECT $gpaSub AS gpa
                   FROM tbl_sinhviens sv
                   WHERE $gpaSub IS NOT NULL) AS t"
        )->fetch(PDO::FETCH_ASSOC);

        return [
            'total_sv' => $totalSV,
            'max_gpa'  => $row['max_gpa']  ?? '—',
            'min_gpa'  => $row['min_gpa']  ?? '—',
            'avg_gpa'  => $row['avg_gpa']  ?? '—',
        ];
    }

    // ── Thống kê từng Ngành ────────────────────────────────────
    public function getThongKeNganh()
    {
        $gpaSub = $this->gpaSub;
        return $this->conn->query(
            "SELECT sv.nganh,
                    COUNT(*) AS so_sv,
                    ROUND(AVG($gpaSub),2) AS avg_gpa
             FROM tbl_sinhviens sv
             GROUP BY sv.nganh
             ORDER BY so_sv DESC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Phân bổ Xếp loại ──────────────────────────────────────
    public function getXepLoaiStats()
    {
        $gpaSub = $this->gpaSub;
        return $this->conn->query(
            "SELECT
               CASE
                 WHEN gpa >= 8.5 THEN 'Xuất Sắc'
                 WHEN gpa >= 7.0 THEN 'Giỏi'
                 WHEN gpa >= 5.5 THEN 'Khá'
                 WHEN gpa >= 4.0 THEN 'Trung Bình'
                 ELSE 'Yếu'
               END AS xep_loai,
               COUNT(*) AS so_luong
             FROM (SELECT $gpaSub AS gpa FROM tbl_sinhviens sv) AS t
             WHERE gpa IS NOT NULL
             GROUP BY xep_loai
             ORDER BY FIELD(xep_loai,'Xuất Sắc','Giỏi','Khá','Trung Bình','Yếu')"
        )->fetchAll(PDO::FETCH_ASSOC);
    }
}
