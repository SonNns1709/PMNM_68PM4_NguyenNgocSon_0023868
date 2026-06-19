<?php
require_once '../app/core/Controller.php';

class Home extends Controller
{
    public function index()
    {
        Middleware::protect();
        $user = Middleware::getUser();

        $this->view("layout/masterlayout", [
            'viewname' => 'home/index',
            'user'     => $user,
        ]);
    }
}
