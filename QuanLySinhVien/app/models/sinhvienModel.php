<?php
require_once '../app/core/ConnectDB.php';

class SinhvienModel
{
    // Đã đồng bộ sang kiểu kết nối PDO
    private \PDO $conn;

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

        // Sử dụng PDO để truy vấn và lấy toàn bộ dữ liệu dưới dạng mảng kết hợp (Associative Array)
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Lấy 1 sinh viên theo ID
    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM sinh_vien WHERE id = :id LIMIT 1";
        
        // Sử dụng Prepared Statement của PDO để bảo mật
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Thêm sinh viên
    public function create(array $data): bool
    {
        $sql = "INSERT INTO sinh_vien (ho_ten, mssv, email, diem, id_lop)
                VALUES (:ho_ten, :mssv, :email, :diem, :id_lop)";

        $stmt = $this->conn->prepare($sql);
        
        // PDO tự động bảo mật dữ liệu đầu vào, không cần dùng mysqli_real_escape_string nữa
        return $stmt->execute([
            'ho_ten' => $data['ho_ten'],
            'mssv'   => $data['mssv'],
            'email'  => $data['email'],
            'diem'   => (float) $data['diem'],
            'id_lop' => (int)   $data['id_lop']
        ]);
    }

    // Cập nhật sinh viên
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE sinh_vien
                SET ho_ten = :ho_ten, email = :email,
                    diem = :diem, id_lop = :id_lop
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute([
            'id'     => $id,
            'ho_ten' => $data['ho_ten'],
            'email'  => $data['email'],
            'diem'   => (float) $data['diem'],
            'id_lop' => (int)   $data['id_lop']
        ]);
    }

    // Xóa sinh viên
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM sinh_vien WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}