<?php

/**
 * CreativeCollection
 *
 * Handle a collection of creatives, retrieve them filtered, sorted etc.
 *
 *
 * @package
 * @version $id$
 * @copyright 2014 Media Decision GmbH
 * @author Christoph 'Kiko' Starkmann <christoph.starkmann@mediadecision.com>
 * @license Proprietary/Closed Source
 */
class CreativeCollection extends ElementCollection
{
    public function __construct()
    {
        parent::__construct('gifpath');
    }
}

