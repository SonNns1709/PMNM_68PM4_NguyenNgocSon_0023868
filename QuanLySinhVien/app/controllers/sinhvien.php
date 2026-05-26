<?php
require_once '../app/models/sinhvienModel.php';

class sinhvien
{
    private $model;

    public function __construct()
    {
        $this->model = new sinhvienModel();
    }

    public function index()
    {
        Middleware::protect();

        $danhSach = $this->model->getAll();

        require_once '../app/views/sinhvien/index.php';
    }
}

