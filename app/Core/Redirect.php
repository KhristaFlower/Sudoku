<?php

namespace Kriptonic\App\Core;

/**
 * Class Redirect
 *
 * Used to instruct the framework to redirect the user to another location.
 *
 * @package Kriptonic\App\Core
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 */
class Redirect extends Response
{
    /**
     * The location this redirect is pointing at.
     * @var string The location to redirect to.
     */
    private $location;

    /**
     * Redirect constructor.
     *
     * @param string $location The location to redirect to.
     */
    public function __construct($location)
    {
        $this->location = $location;
    }

    /**
     * Perform the redirect.
     */
    public function handle()
    {
        header('Location: /' . $this->location);
    }
}
