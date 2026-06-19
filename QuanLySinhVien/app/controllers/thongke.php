<?php
require_once '../app/core/Controller.php';

class Thongke extends Controller
{
    public function index()
    {
        Middleware::protect();

        $model     = $this->model('thongkeModel');
        $tongQuan  = $model->getTongQuan();
        $theoNganh = $model->getThongKeNganh();
        $xepLoai   = $model->getXepLoaiStats();

        $this->view("layout/masterlayout", [
            'viewname'  => 'thongke/index',
            'tongQuan'  => $tongQuan,
            'theoNganh' => $theoNganh,
            'xepLoai'   => $xepLoai,
        ]);
    }
}