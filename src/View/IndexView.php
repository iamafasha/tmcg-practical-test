<?php

    namespace App\View;
    /**
    * The home page view
    */
    class IndexView
    {

        private $model;

        private $controller;


        function __construct($controller, $model)
        {
            $this->controller = $controller;

            $this->model = $model;

        }

        public function index()
        {
            return $this->controller->index();
        }

        public function action()
        {
            return $this->controller->takeAction();
        }

    }