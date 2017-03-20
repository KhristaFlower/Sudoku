<?php

namespace Kriptonic\Puzzle;

class PuzzleState
{
    /**
     * The state version.
     * This will be updated when the save format changes and allows for handling
     * of backwards compatible changes.
     */
    const VERSION = 1;

    /**
     * The Sudoku puzzle object.
     * @var Sudoku
     */
    private $sudoku;

    /**
     * An array to hold the players pencil choices.
     * @var array
     */
    private $pencils;

    /**
     * Set the Sudoku object that this State should track.
     *
     * @param Sudoku $sudoku The Sudoku puzzle to store on the state.
     */
    public function setSudoku(Sudoku $sudoku)
    {
        $this->sudoku = $sudoku;
    }

    /**
     * Set the pencil information.
     *
     * @param array $pencils The array of pencil data.
     */
    public function setPencils(array $pencils)
    {
        $this->pencils = $pencils;
    }

    public function toString()
    {
        
    }

}

