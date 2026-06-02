<?php
class Controller
{
    // 1. Định nghĩa kiểu dữ liệu string cho $model và kiểu trả về là đối tượng object
    public function model(string $model): object
    {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    // 2. Định nghĩa kiểu dữ liệu string cho $view và array cho $data
    public function view(string $view, array $data = []): void
    {
        // extract() biến mảng thành biến: $viewname, $sinhviens...
        extract($data);
        require_once '../app/views/' . $view . '.php';
    }
}
