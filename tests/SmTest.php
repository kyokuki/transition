<?php

namespace transition\tests;

use transition\Callback;
use transition\StaterInterface;

class SmTest
{
    public function test()
    {
        $stm = new \transition\StateMachine();
        $stm->initial("draft");
        $stm->state("checkout")->setEnter(
            new Callback(
                function ($val) {
                    echo sprintf("checkout : state enter \n");
                })
        )->setQuit(
            new Callback(
                function ($val) {
                    echo sprintf("checkout : state quit \n");
                })
        );
        $stm->state("paid");
        $stm->state("processed");
        $stm->state("canceled");

        $stm->event("checkout")->setTo("checkout")->setFrom(['draft'])
            ->setBefore(new Callback(
                function ($val) {
                    echo sprintf("checkout : before func \n");
                }))
            ->setAfter(new Callback(
                function ($val) {
                    echo sprintf("checkout : after func \n");
                }));

        $stm->event("cancel")->setTo('canceled')->setFrom(['checkout'])
            ->setBefore(new Callback(
                function ($val) {
                    echo sprintf("cancel : before func \n");
                }))
            ->setAfter(new Callback(
                function ($val) {
                    echo sprintf("cancel : after func \n");
                }));

        $order = [
            'status' => 'draft',
            'data' => '12121212'
        ];
        $obj = new StaterClass($order, 'status');

        try {
            $stm->trigger("checkout", $obj);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }

        var_dump($obj->getData());

        try {
            $stm->trigger("cancel", $obj);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }

        var_dump($obj->getData());
    }
}


class StaterClass implements StaterInterface
{

    private $data;
    private $key;

    public function __construct(&$mapArr, $mapKey)
    {
        $this->data = $mapArr;
        $this->key = $mapKey;
        return $this;
    }

    public function setState(string $name)
    {
        $this->data[$this->key] = $name;
    }

    public function getState()
    {
        return $this->data[$this->key];
    }

    public function getData()
    {
        return $this->data;
    }
}

$ttt = new SmTest();
$ttt->test();