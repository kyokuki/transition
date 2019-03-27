<?php
declare(strict_types=1);

namespace transition;

class EventTransition
{
    public $to;
    public $froms;     // array string
    public $befores;   // array func
    public $afters;    // array func

    public function __construct(string $to)
    {
        $this->froms = [];
        $this->befores = [];
        $this->afters = [];
        $this->setTo($to);
        return $this;
    }

    public function setTo(string $to): EventTransition
    {
        $this->to = $to;
        return $this;
    }

    public function setFrom(array $states): EventTransition
    {
        if (is_string($states)) {
            $this->froms = [$states];
        } else {
            $this->froms = $states;
        }
        return $this;
    }

    public function setBefore(Callback $cb): EventTransition
    {
        $this->befores[] = $cb;
        return $this;
    }

    public function setAfter(Callback $cb): EventTransition
    {
        $this->afters[] = $cb;
        return $this;
    }
}