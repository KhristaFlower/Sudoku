<?php

namespace Kriptonic\App\Controllers;

use Kriptonic\App\Core\Request;
use Kriptonic\Sudoku\Puzzle\Sudoku;

/**
 * Class PuzzleController
 *
 * @package Kriptonic\App\Controllers
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 */
class PuzzleController
{
    /**
     * Load the page and play the game.
     *
     * @return \Kriptonic\App\Core\Response
     */
    public function play()
    {
        return $this->handlePuzzle();
    }

    /**
     * Load the page, play the game, and validate the players answers.
     *
     * @return \Kriptonic\App\Core\Response
     */
    public function validate()
    {
        return $this->handlePuzzle();
    }

    /**
     * A utility method used by both get and post methods for loading form data and handling it.
     *
     * @return \Kriptonic\App\Core\Response
     */
    private function handlePuzzle()
    {
        $seed = Request::input('seed', rand(0, PHP_INT_MAX));
        $oldSeed = Request::input('oldSeed', null);
        $difficulty = Request::input('difficulty', 3);
        $showErrors = Request::input('showErrors', false);
        $playerPencils = [];

        $puzzle = new Sudoku($seed, $difficulty);

        // Don't validate the player answers if they have also changed the seed.
        if ($seed === $oldSeed) {
            $puzzle->validate(Request::input('playerInput', []));
            $playerPencils = Request::input('playerPencils', []);
        }

        return view('play', compact('puzzle', 'seed', 'difficulty', 'showErrors', 'playerPencils'));
    }
}
