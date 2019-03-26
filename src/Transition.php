<?php

namespace transition;


class Transition
{
    public $state;

    public function setState(string $name)
    {
        $this->state = name;
    }

    public function getState()
    {
        return $this->state;
    }
}