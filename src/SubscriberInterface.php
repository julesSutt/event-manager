<?php

namespace Jules\EventManager;

interface SubscriberInterface
{
    /**
     * Return events to listen.
     * key -> callback
     */
    public function getEvents(): array;
}
