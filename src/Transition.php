<?php
declare(strict_types=1);

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