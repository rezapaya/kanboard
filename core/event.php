<?php

namespace Core;

/**
 * Event listener interface
 *
 * @package core
 * @author  Frederic Guillot
 */
interface Listener {
    public function execute(array $data);
}

/**
 * Event dispatcher class
 *
 * @package core
 * @author  Frederic Guillot
 */
class Event
{
    /**
     * Contains all events
     *
     * @access private
     * @var array
     */
    private $events = array();

    /**
     * Attach a listener object to an event
     *
     * @access public
     * @param  string   $eventName   Event name
     * @param  Listener $listener    Object that implements the Listener interface
     */
    public function attach($eventName, Listener $listener)
    {
        if (! isset($this->events[$eventName])) {
            $this->events[$eventName] = array();
        }

        $this->events[$eventName][] = $listener;
    }

    /**
     * Trigger an event
     *
     * @access public
     * @param  string   $eventName   Event name
     * @param  array    $data        Event data
     */
    public function trigger($eventName, array $data)
    {
        if (isset($this->events[$eventName])) {
            foreach ($this->events[$eventName] as $listener) {
                $listener->execute($data);
            }
        }
    }
}
