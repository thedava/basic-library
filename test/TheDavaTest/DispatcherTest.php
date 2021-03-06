<?php

namespace TheDavaTest;

use TheDava\Dispatcher;
use TheDavaTest\Mock\Controller\PHPUnitController;
use TheDavaTest\Mock\Controller\PHPUnitDefaultController;
use TheDavaTest\Mock\Controller\PHPUnitErrorController;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    const PARAM_CONTROLLER = 'controller';
    const PARAM_ACTION = 'action';

    public static function getDispatcherOptions()
    {
        return [
            'controller_param'   => static::PARAM_CONTROLLER,
            'controller_prefix'  => 'TheDavaTest\\Mock\\Controller\\',
            'controller_default' => 'PHPUnitDefault',
            'controller_error'   => 'PHPUnitError',
            'action_param'       => static::PARAM_ACTION,
        ];
    }

    /**
     * @param array $params
     *
     * @return Dispatcher
     */
    protected function getDispatcher($params = [])
    {
        return new Dispatcher($params, $this->getDispatcherOptions());
    }

    public function testDispatchSuccessWithDefaultController()
    {
        $dispatcher = $this->getDispatcher();
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit_Default', $result);
        $this->assertEquals(PHPUnitDefaultController::class, $dispatcher->getController());
        $this->assertEquals('indexAction', $dispatcher->getAction());

        $this->assertEquals('PHPUnitDefault', $dispatcher->getController(true));
        $this->assertEquals('index', $dispatcher->getAction(true));
    }

    public function testDispatchSuccessWithSpecifiedController()
    {
        $dispatcher = $this->getDispatcher([
            static::PARAM_CONTROLLER => 'PHPUnit',
        ]);
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit', $result);
        $this->assertEquals(PHPUnitController::class, $dispatcher->getController());
        $this->assertEquals('indexAction', $dispatcher->getAction());

        $this->assertEquals('PHPUnit', $dispatcher->getController(true));
        $this->assertEquals('index', $dispatcher->getAction(true));
    }

    public function testDispatchSuccessWithSpecifiedControllerAndAction()
    {
        $dispatcher = $this->getDispatcher([
            static::PARAM_CONTROLLER => 'PHPUnit',
            static::PARAM_ACTION     => 'test',
        ]);
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit_Test', $result);
        $this->assertEquals(PHPUnitController::class, $dispatcher->getController());
        $this->assertEquals('testAction', $dispatcher->getAction());

        $this->assertEquals('PHPUnit', $dispatcher->getController(true));
        $this->assertEquals('test', $dispatcher->getAction(true));
    }

    public function testDispatchSuccessWithSpecifiedControllerAndActionAndWithParamsAndDefaultValue()
    {
        $dispatcher = $this->getDispatcher([
            static::PARAM_CONTROLLER => 'PHPUnit',
            static::PARAM_ACTION     => 'params',
            'param1'                 => 'foo',
        ]);
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit_Params:foo:bar', $result);
        $this->assertEquals(PHPUnitController::class, $dispatcher->getController());
        $this->assertEquals('paramsAction', $dispatcher->getAction());

        $this->assertEquals('PHPUnit', $dispatcher->getController(true));
        $this->assertEquals('params', $dispatcher->getAction(true));
    }

    public function testDispatchSuccessWithSpecifiedControllerAndActionAndWithAllParams()
    {
        $dispatcher = $this->getDispatcher([
            static::PARAM_CONTROLLER => 'PHPUnit',
            static::PARAM_ACTION     => 'params',
            'param1'                 => 'foo',
            'param2'                 => 'baz',
        ]);
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit_Params:foo:baz', $result);
        $this->assertEquals(PHPUnitController::class, $dispatcher->getController());
        $this->assertEquals('paramsAction', $dispatcher->getAction());

        $this->assertEquals('PHPUnit', $dispatcher->getController(true));
        $this->assertEquals('params', $dispatcher->getAction(true));
    }

    public function testDispatchFailWithMissingParam()
    {
        $dispatcher = $this->getDispatcher([
            static::PARAM_CONTROLLER => 'PHPUnit',
            static::PARAM_ACTION     => 'params',
        ]);

        $this->setExpectedException(\Exception::class, 'The parameter "param1" is required and missing!');
        echo $dispatcher->dispatch();
    }

    public function testDispatchFailWithEmptyValues()
    {
        $dispatcher = $this->getDispatcher([
            static::PARAM_CONTROLLER => '',
            static::PARAM_ACTION     => '',
        ]);
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit_Error', $result);
        $this->assertEquals(PHPUnitErrorController::class, $dispatcher->getController());
        $this->assertEquals('indexAction', $dispatcher->getAction());

        $this->assertEquals('PHPUnitError', $dispatcher->getController(true));
        $this->assertEquals('index', $dispatcher->getAction(true));
    }

    public function testDispatchFailWithControllerNotFound()
    {
        $dispatcher = $this->getDispatcher([
            static::PARAM_CONTROLLER => 'PHPUnitNotExists',
            static::PARAM_ACTION     => '',
        ]);
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit_Error', $result);

        $match = false;
        foreach ($dispatcher->getLastErrors() as $error) {
            if (strpos($error, 'There is no controller') === 0) {
                $match = true;
                break;
            }
        }
        $this->assertTrue($match, 'The expected "There is no controller" error message is missing!');
    }

    public function testDispatchFailWithControllerNotChildOfAbstractController()
    {
        $dispatcher = $this->getDispatcher([
            static::PARAM_CONTROLLER => 'PHPUnitOrphan',
            static::PARAM_ACTION     => '',
        ]);
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit_Error', $result);

        $match = false;
        foreach ($dispatcher->getLastErrors() as $error) {
            if (strpos($error, 'does not extend') !== false) {
                $match = true;
                break;
            }
        }
        $this->assertTrue($match, 'The expected "does not extend" error message is missing!');
    }

    public function testDispatchFailWithInvalidAction()
    {
        $dispatcher = $this->getDispatcher([
            static::PARAM_CONTROLLER => 'PHPUnit',
            static::PARAM_ACTION     => 'notExists',
        ]);
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit', $result); // indexAction

        $match = false;
        foreach ($dispatcher->getLastErrors() as $error) {
            if (strpos($error, 'There is no action') !== false) {
                $match = true;
                break;
            }
        }
        $this->assertTrue($match, 'The expected "There is no action" error message is missing!');
    }

    public function testDispatchFailWithProtectedAction()
    {
        $dispatcher = $this->getDispatcher([
            static::PARAM_CONTROLLER => 'PHPUnit',
            static::PARAM_ACTION     => 'protected',
        ]);
        $result = $dispatcher->dispatch();

        $this->assertEquals('PHPUnit', $result); // indexAction

        $match = false;
        foreach ($dispatcher->getLastErrors() as $error) {
            if (strpos($error, 'is not supported') !== false) {
                $match = true;
                break;
            }
        }
        $this->assertTrue($match, 'The expected "is not supported" error message is missing!');
    }

    public function testGetOptions()
    {
        $this->assertArraySubset(static::getDispatcherOptions(), $this->getDispatcher()->getOptions());
    }
}
