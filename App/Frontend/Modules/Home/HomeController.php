<?php

namespace App\Frontend\Modules\Home;

use \OCFram\BackController;
use \OCFram\HTTPRequest;

class HomeController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        $this->addView();
    }
}
