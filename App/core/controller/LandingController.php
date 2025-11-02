<?php

namespace App\core\controller;

class LandingController
{

    public function renderLanding($engine)
    {
        echo $engine->render('pages/home');
    }
}
