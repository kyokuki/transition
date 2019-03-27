<?php
declare(strict_types=1);

namespace transition;


class Callback
{
    private $func;

    function __construct(\Closure $func)
    {
        $this->setFunc($func);
    }

    private function setFunc(\Closure $func)
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