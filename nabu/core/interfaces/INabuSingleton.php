<?php

namespace nabu\core\interfaces;

/**
 * Empty interface to identify Singleton classes.
 * @author Rafael Gutiérrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surfae
 * @version 3.0.12 Surface
 */
interface INabuSingleton
{
    /**
     * Check if a Singleton object is instantiated.
     * @return bool Returns true if is instantiated.
     */
    public static function isInstantiated() : bool;
}
