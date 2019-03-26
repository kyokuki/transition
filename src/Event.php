<?php

namespace transition;


class Event
{
    public $name;
    public $transitions;   // array of EventTransition

    public function __construct(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function setTo(string $name)
    {
        $transition = new EventTransition($name);
        $this->transitions[] = $transition;
        return $transition;
    }

}