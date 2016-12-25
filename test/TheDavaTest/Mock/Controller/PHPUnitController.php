<?php

namespace TheDavaTest\Mock\Controller;

use TheDava\Controller\AbstractController;

class PHPUnitController extends AbstractController
{
    /**
     * Default action of every controller
     *
     * @return mixed
     */
    public function indexAction()
    {
        return 'PHPUnit';
    }

    public function testAction()
    {
        return 'PHPUnit_Test';
    }

    public function foobarAction()
    {
        return 'PHPUnit_FooBar';
    }

    public function paramsAction($param1, $param2 = 'bar')
    {
        return 'PHPUnit_Params:' . $param1 . ':' . $param2;
    }

    protected function protectedAction()
    {
        return 'PHPUnit_Protected';
    }
}
