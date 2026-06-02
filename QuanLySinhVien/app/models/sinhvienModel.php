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
}