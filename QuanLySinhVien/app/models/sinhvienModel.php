<?php
require_once '../app/core/ConnectDB.php';

class SinhvienModel
{
    // 1. Sửa lỗi thuộc tính $conn ở dòng 6
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = ConnectDB::getInstance()->getConnection();
    }

    // Lấy toàn bộ sinh viên (JOIN với bảng lop)
    public function getAll(): array
    {
        $sql = "SELECT sv.id, sv.ho_ten, sv.mssv, sv.email,
                       sv.diem, l.ten_lop
                FROM sinh_vien sv
                LEFT JOIN lop l ON sv.id_lop = l.id
                ORDER BY sv.id ASC";

        $result = mysqli_query($this->conn, $sql);
        $data   = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }

        return $data;
    }

    // 2. Sửa lỗi tham số $id (kiểu int) ở dòng 33
    public function getById(int $id): ?array
    {
        $id  = (int) $id;
        $sql = "SELECT * FROM sinh_vien WHERE id = $id LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_assoc($result) ?: null;
    }

    // 3. Sửa lỗi tham số $data (kiểu array) ở dòng 42
    public function create(array $data): bool|\mysqli_result
    {
        $ho_ten = mysqli_real_escape_string($this->conn, $data['ho_ten']);
        $mssv   = mysqli_real_escape_string($this->conn, $data['mssv']);
        $email  = mysqli_real_escape_string($this->conn, $data['email']);
        $diem   = (float) $data['diem'];
        $id_lop = (int)   $data['id_lop'];

        $sql = "INSERT INTO sinh_vien (ho_ten, mssv, email, diem, id_lop)
                VALUES ('$ho_ten','$mssv','$email',$diem,$id_lop)";

        return mysqli_query($this->conn, $sql);
    }

    // 4. Sửa lỗi tham số $id và $data ở dòng 57
    public function update(int $id, array $data): bool|\mysqli_result
    {
        $id     = (int) $id;
        $ho_ten = mysqli_real_escape_string($this->conn, $data['ho_ten']);
        $email  = mysqli_real_escape_string($this->conn, $data['email']);
        $diem   = (float) $data['diem'];
        $id_lop = (int)   $data['id_lop'];

        $sql = "UPDATE sinh_vien
                SET ho_ten='$ho_ten', email='$email',
                    diem=$diem, id_lop=$id_lop
                WHERE id=$id";

        return mysqli_query($this->conn, $sql);
    }

    // 5. Sửa lỗi tham số $id ở dòng 74
    public function delete(int $id): bool|\mysqli_result
    {
        $id  = (int) $id;
        $sql = "DELETE FROM sinh_vien WHERE id=$id LIMIT 1";
        return mysqli_query($this->conn, $sql);
    }
}
