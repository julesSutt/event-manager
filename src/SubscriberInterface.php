<?php

/**
 * @file        : SubscriberInterface.php
 * @date        : 06/07/2023
 *
 * @description : Interface for add a subscriber to the event manager.
 * @copyright   : (c) Jules Sütterlin - All rights reserved
 */

namespace EventManager;

/**
 * Interface SubscriberInterface
 * @package EventManager
 */
interface SubscriberInterface
{
    /**
     * Return events to listen.
     * key -> callback
     */
    public function getEvents(): array;
}
