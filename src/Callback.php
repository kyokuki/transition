<?php

namespace transition;


class Callback
{
    private $func;

    function __construct($func)
    {
        $this->SetFunc($func);
    }

    private function setFunc($func)
    {
        if (gettype($func) != 'object') {
            throw new \Exception('Parameter of CallBack.SetFunc() must be a Closure.');
        }
        $this->func = $func;
    }

    public function execute($param)
    {
        if (empty($this->func)) {
            throw new \Exception('CallBack need initialize');
        }

        ($this->func)($param);
    }
}