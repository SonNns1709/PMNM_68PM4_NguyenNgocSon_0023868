<?php

require_once __DIR__ . '/ConnectDB.php';

class App
{
    protected $controller = 'Home';
    protected $action = 'index';
    protected $params = [];

    public function __construct()
    {
        $urlProcessed = $this->urlProcess();

        // Sửa lỗi S1066: Gộp 2 lệnh if lồng nhau thành 1 bằng toán tử &&
        if (isset($urlProcessed[0]) && file_exists('../app/controllers/' . $urlProcessed[0] . '.php')) {
            $this->controller = $urlProcessed[0];
            unset($urlProcessed[0]);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        
        // Khởi tạo đối tượng từ tên controller
        $this->controller = new $this->controller();

        // Sửa lỗi S1066: Gộp tiếp 2 lệnh if lồng nhau
        if (isset($urlProcessed[1]) && method_exists($this->controller, $urlProcessed[1])) {
            $this->action = $urlProcessed[1];
            unset($urlProcessed[1]);
        }

        $this->params = $urlProcessed ? array_values($urlProcessed) : [];
        
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    // Sửa lỗi S100: Đổi tên từ UrlProcess (PascalCase) sang urlProcess (camelCase)
    public function urlProcess()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(trim($_GET['url'], '/')));
        }
        return []; // Nên trả về mảng rỗng thay vì null để tránh lỗi ở __construct
    }
}


