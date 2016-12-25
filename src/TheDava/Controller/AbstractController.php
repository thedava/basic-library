<?php

namespace TheDava\Controller;

use TheDava\Dispatcher;
use TheDava\DispatcherAwareTrait;

abstract class AbstractController
{
    use DispatcherAwareTrait;

    /**
     * AbstractController constructor.
     *
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Default action of every controller
     *
     * @return mixed
     */
    abstract public function indexAction();
}
