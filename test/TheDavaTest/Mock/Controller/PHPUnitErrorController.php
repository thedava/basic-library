<?php

namespace TheDavaTest\Mock\Controller;

use TheDava\Controller\AbstractController;

class PHPUnitErrorController extends AbstractController
{
    /**
     * Default action of every controller
     *
     * @return mixed
     */
    public function indexAction()
    {
        return 'PHPUnit_Error';
    }
}
