<?php

namespace nabu\core\interfaces;
use nabu\data\CNabuDataObject;

/**
 * Empty interface to identify Singleton classes.
 * @author Rafael Gutiérrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surfae
 * @version 3.0.12 Surface
 */
interface INabuHashed
{
    /**
     * Gets the hash.
     * @return string Returns the hash assigned or null if no hash found.
     */
    public function getHash();

    /**
     * Sets a hash.
     * @param string $hash The hash to be setted.
     * @return mixed Returns the self instance to grant chained setters mechanism.
     */
    public function setHash(string $hash = null) : CNabuDataObject;

    /**
     * Grants that this object have a valid hash. Optionally can save the hash in the database.
     * @param bool $save If true, the save() method is called after grant the hash and just before return.
     * @return string Returns a valid hash.
     */
    public function grantHash(bool $save) : string;
}
