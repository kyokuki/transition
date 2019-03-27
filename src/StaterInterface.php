<?php

namespace transition;


interface StaterInterface
{
    public function setState(string $name);

    public function getState();

    public function &getData();
}