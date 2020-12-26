<?php

namespace App\Controller;

    class IndexController
    {
        private $model;

        function __construct($model)
        {
            $this->model = $model;
        }

        public function index()
        {
            return;
        }


    }