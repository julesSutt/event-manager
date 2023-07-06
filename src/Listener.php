<?php

/**
 * @file        : Listener.php
 * @date        : 06/07/2023
 *
 * @description : Event listener, call the callback when the event is triggered
 * @copyright   : (c) Jules Sütterlin - All rights reserved
 */

namespace EventManager;

/**
 * Class Listener
 *
 * @package Jules\EventManager
 */
class Listener
{

    /**
     * The callback
     * 
     * @var callable
     */
    public $callback;

    /**
     * The priority of listener
     * 
     * @var int
     */
    public int $priority;

    /**
     * Set if the listener can be called multiple times
     *
     * @var bool
     */
    private bool $once = false;

    /**
     * Stops parent events
     *
     * @var bool
     */
    public bool $stopPropagation = false;

    /**
     * Shows how many times the listener has been called
     * @var bool
     */
    private int $calls = 0;

    /**
     * Listener constructor.
     *
     * @param callable $callback
     * @param int $priority
     */
    public function __construct(callable $callback, int $priority)
    {
        $this->callback = $callback;
        $this->priority = $priority;
    }

    /**
     * Call the callback
     *
     * @param array $args
     * @return mixed
     */
    public function handle(array $args)
    {
        if ($this->once && $this->calls > 0) {
            return null;
        }
        $this->calls++;
        return call_user_func_array($this->callback, $args);
    }

    /**
     * Indicates that the listener can only be called once
     *
     * @return Listener
     */
    public function once(): Listener
    {
        $this->once = true;
        return $this;
    }

    /**
     * Stops the execution of the following events
     *
     * @return Listener
     */
    public function stopPropagation(): Listener
    {
        $this->stopPropagation = true;
        return $this;
    }
}
