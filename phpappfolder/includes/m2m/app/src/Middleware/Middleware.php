<?php

namespace M2m\Middleware;

class Middleware
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
}