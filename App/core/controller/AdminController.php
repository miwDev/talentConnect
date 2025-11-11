<?php

namespace App\core\controller;

class AdminController
{

    public function renderList($engine)
    {
        echo $engine->render('Admin/adminDashboard');
    }

    public function renderDashboard($engine) {}
}
