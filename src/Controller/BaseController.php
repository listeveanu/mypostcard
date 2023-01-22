<?php

declare(strict_types=1);

namespace Cezar\Mypostcard\Controller;

use Cezar\Mypostcard\Util\View;

abstract class BaseController {

    protected View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    /**
     * @return false|string
     */
    public function render(): false|string
    {
        return $this->view->loadTemplate();
    }
}