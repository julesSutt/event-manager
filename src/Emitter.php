<?php

/**
 * @file        : Emitter.php
 * @date        : 06/07/2023
 *
 * @description : Event emitter 
 * @copyright   : (c) Jules Sütterlin - All rights reserved
 */

namespace EventManager;

use EventManager\Listener;

/**
 * Event emitter
 * @package : Jules\EventManager
 */
class Emitter
{
    /**
     * Instance of Emitter
     * @var Emitter
     */
    private static $_instance;

    /**
     * Save list of listeners
     * @var array
     */
    private array $listeners = [];

    /**
     * Get instance of Emitter
     * singleton
     * 
     * @return Emitter
     */
    public static function getInstance(): self
    {
        if (!self::$_instance) {
            self::$_instance = new Emitter();
        }

        return self::$_instance;
    }

    /**
     * Send an event to all listeners
     * 
     * @param string $event
     * @param mixed ...$args
     */
    public function emit(string $event, ...$args)
    {
        if ($this->hasListener($event)) {
            foreach ($this->listeners[$event] as $listener) {
                $listener->handle($args);
                if ($listener->stopPropagation) {
                    break;
                }
            }
        }
    }

    /**
     * Listen to an event
     * 
     * @param string $event
     * @param callable $callable
     * @param int $priority = 0
     */
    public function on(string $event, callable $callable, int $priority = 0): Listener
    {
        if (!$this->hasListener($event)) {
            $this->listeners[$event] = [];
        }
        $this->checkDoubleCallableForEvent($event, $callable);
        $listener = new Listener($callable, $priority);
        $this->listeners[$event][] = $listener;
        $this->sortListeners($event);
        return $listener;
    }

    /**
     * Listen to an event and call once
     *
     * @param string $event
     * @param callable $callable
     * @param int $priority
     * @return Listener
     */
    public function once(string $event, callable $callable, int $priority = 0): Listener
    {
        return $this->on($event, $callable, $priority)->once();
    }

    /**
     * Add a subscriber to the emitter
     * for lisent multiple events
     *
     * @param SubscriberInterface $subscriber
     */
    public function addSubscriber(SubscriberInterface $subscriber)
    {
        $events = $subscriber->getEvents();
        foreach ($events as $event => $method) {
            $this->on($event, [$subscriber, $method]);
        }
    }

    /**
     * Check if an event has listeners
     * 
     * @param string $event
     * @return bool
     */
    private function hasListener(string $event): bool
    {
        return array_key_exists($event, $this->listeners);
    }

    /**
     * Sort listeners by priority
     * 
     * @param string $event
     */
    private function sortListeners(string $event): void
    {
        uasort($this->listeners[$event], function ($a, $b) {
            return $a->priority < $b->priority;
        });
    }

    /**
     * Check if a callable is a double event
     * 
     * @param string $event
     * @param callable $callable
     * @return bool
     */
    private function checkDoubleCallableForEvent(string $event, callable $callable): bool
    {
        foreach ($this->listeners[$event] as $listener) {
            if ($listener->callback === $callable) {
                throw new DoubleEventException();
            }
        }
        return false;
    }
}
