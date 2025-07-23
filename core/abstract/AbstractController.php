<?php

    namespace App\Core\Abstract;

    use App\Core\App;
    use App\Core\Session;

    abstract class AbstractController
    {

        protected $layout = "layouts/base.layout";
        protected Session $session;

        public function __construct()
        {
            $this->session = App::getDependancy('session');
        }


        public function renderHtml(string $view, array $data = [])
        {
            extract($data);

            ob_start();
            require_once "../templates/" . $view . ".html.php";
            $contentForLayout = ob_get_clean();
            require_once "../templates/" . $this->layout . ".html.php";
        }
        

        abstract public function index();
        abstract public function edit();
        abstract public function destroy(); 
        abstract public function store();
        abstract public function show();
        abstract public function create();

    }
