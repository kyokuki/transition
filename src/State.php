<?php

namespace transition;

class State implements StaterInterface
{
    public $name;
    public $enters;    // array
    public $exites;    // array

    public function __construct(string $name)
    {
        if (empty($name)) {
            throw new \Exception('State.constructor need param.');
        }
        $this->name = $name;
        $this->enters = [];
        $this->exites = [];
        return $this;
    }

    public function setEnter(Callback $cb)
    {
        if (empty($cb)) {
            throw new \Exception('State.Enter() need param.');
        }
        $this->enters[] = $cb;
        return $this;
    }

    public function setQuit(Callback $cb)
    {
        if (empty($cb)) {
            throw new Exception('State.Quit() need param.');
        }
        $this->exites[] = $cb;
        return $this;
    }

    public function setState(string $name)
    {
        $this->name = $name;
    }

    public function getState()
    {
        return $this->name;
    }

    public function getData()
    {
        return null;
    }
}