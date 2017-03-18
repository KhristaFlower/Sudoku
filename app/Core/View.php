<?php

namespace Kriptonic\App\Core;

use Kriptonic\App\Models\User;

/**
 * Class View
 *
 * Used to instruct the framework to display a page to the user.
 *
 * @package Kriptonic\App\Core
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 */
class View extends Response
{
    /**
     * The name of the view file to display.
     * @var string The name of the view file.
     */
    private $viewFile;

    /**
     * An array of data to be used in the view.
     * @var array Data to be used in the view.
     */
    private $viewData;

    /**
     * View constructor.
     *
     * @param string $viewFile The name of the view to load.
     * @param array $data An array of parameters to be used on the view.
     */
    public function __construct($viewFile, $data = [])
    {
        $this->viewFile = $viewFile;
        $this->viewData = $data;
    }

    /**
     * Display the view page and make the data variables available for use.
     */
    public function handle()
    {
        extract($this->viewData);

        // Make the currently logged in User object available in the views.
        $currentUser = User::current();

        // $pageInclude is required inside of master.layout.php
        $pageInclude = '../app/views/' . $this->viewFile . '.php';

        require '../app/views/master.layout.php';
    }
}
