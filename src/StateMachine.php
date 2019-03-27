<?php
declare(strict_types=1);

namespace transition;


class StateMachine
{
    private $initialState;
    private $states;    // map[string => State]
    private $events;    // map[string => Event]

    public function __construct()
    {
        $ver = '7.0.0';
        if (version_compare(PHP_VERSION, $ver) < 0) {
            die('Need PHP: ' . $ver . ' or above (current is: ' . PHP_VERSION . ')');
        }
        return $this;
    }

    public function initial(string $name)
    {
        $this->initialState = $name;
        $this->states = [];
        $this->events = [];
        return $this;
    }

    public function state(string $name): State
    {
        $state = new State($name);
        $this->states[$name] = $state;
        return $state;
    }

    public function event(string $name): Event
    {
        $event = new Event($name);
        $this->events[$name] = $event;
        return $event;
    }

    public function trigger(string $paramName, StaterInterface $paramValue = null)
    {
        $stateWas = $paramValue->getState();
        if ($stateWas == "") {
            $stateWas = $this->initialState;
        }

        if (!isset($this->events[$paramName])) {
            throw new \Exception(sprintf('failed to perform event %s from state %s', $paramName, $stateWas));
        }

        $_tmpEvent = $this->events[$paramName];
        $matchedEventTransitions = []; // array EventTransition
        foreach ($_tmpEvent->transitions as $_tmpIdx => &$_tmpEventTransition) {
            $validFrom = count($_tmpEventTransition->froms) == 0;
            if (count($_tmpEventTransition->froms)) {
                foreach ($_tmpEventTransition->froms as $_tmpIdx => $_tmpEventTransitionFromsValue) {
                    if ($stateWas == $_tmpEventTransitionFromsValue) {
                        $validFrom = true;
                    }
                }
            }

            if ($validFrom) {
                $matchedEventTransitions[] = $_tmpEventTransition;
            }
        }

        if (count($matchedEventTransitions) == 1) {
            $oneMatchedEventTransition = $matchedEventTransitions[0];

            // State: exit
            if (isset($this->states[$stateWas])) {
                $_tmpStates = $this->states[$stateWas];
                foreach ($_tmpStates->exites as $_tmpIdx => $_tmpStatesExitesFunc) {
                    $_tmpStatesExitesFunc->execute($paramValue);
                }
            }

            // Transition: before
            foreach ($oneMatchedEventTransition->befores as $_tmpIdx => $oneMatchedTransitionBeforesFunc) {
                $oneMatchedTransitionBeforesFunc->execute($paramValue);
            }

            $paramValue->setState($oneMatchedEventTransition->to);

            // State: enter
            if (isset($this->states[$oneMatchedEventTransition->to])) {
                $_tmpMatchedState = $this->states[$oneMatchedEventTransition->to];
                foreach ($_tmpMatchedState->enters as $_tmpIdx => $_tmpMatchedStateEntersFunc) {
                    try {
                        $_tmpMatchedStateEntersFunc->execute($paramValue);
                    } catch (\Exception $ex) {
                        $paramValue->setState($stateWas);
                        throw $ex;
                    }
                }
            }

            // Transition: after
            foreach ($oneMatchedEventTransition->afters as $_tmpIdx => $oneMatchedTransitionAftersFunc) {
                try {
                    $oneMatchedTransitionAftersFunc->execute($paramValue);
                } catch (\Exception $ex) {
                    $paramValue->setState($stateWas);
                    throw $ex;
                }
            }
        }
    }
}