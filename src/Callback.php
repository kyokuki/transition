<?php

namespace transition;


class Callback
{
    private $func;

    function __construct($func)
    {
        $this->setFunc($func);
    }

    private function setFunc($func)
    {
        if (gettype($func) != 'object') {
            throw new \Exception('Parameter of CallBack.SetFunc() must be a Closure.');
        }
        $this->func = $func;
    }

    public function execute(StaterInterface $param)
    {
        if (empty($this->func)) {
            throw new \Exception('CallBack need initialize');
        }

        ($this->func)($param);
    }
}