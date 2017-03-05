<?php

namespace Kriptonic\Sudoku;

/**
 * Class Sudoku
 *
 * Used to generate Sudoku puzzles.
 *
 * @package Kriptonic\Sudoku
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 */
class Sudoku
{
    /**
     * The modifier for the difficulty; this value will be used with the difficulty
     * level to select the number of cells to hide.
     * @const int Difficulty modifier.
     */
    const DIFFICULTY_MODIFIER = 7;

    /**
     * The seed to use for puzzle generation.
     * @var null|int The seed.
     */
    private $seed;

    /**
     * The difficulty the puzzle was generated with.
     * @var int The difficulty.
     */
    private $difficulty;

    /**
     * The game-ready puzzle.
     * @var null|array|Cell[] The game grid.
     */
    private $puzzle;

    /**
     * Sudoku constructor.
     *
     * Generate a Sudoku puzzle.
     *
     * @param string $seed The seed to use for puzzle generation.
     * @param int $difficulty The difficulty level.
     */
    public function __construct($seed, $difficulty)
    {
        // Generate a seed if we don't have one.
        $this->seed = $seed ?: time();
        $this->difficulty = $difficulty;

        $this->generatePuzzle($difficulty);
    }

    /**
     * Generate a puzzle with the provided difficulty level.
     *
     * @param int $difficulty The difficulty level.
     */
    private function generatePuzzle($difficulty)
    {
        // Seed the random number generator for consistent puzzle generation.
        srand($this->seed);

        $scrambledGrid = (new Scrambler($this->getBaseGrid()))->getGrid();

        // Make the game grid.
        $gameGrid = $this->getGameGrid($scrambledGrid);

        $this->puzzle = $this->setDifficulty($gameGrid, $difficulty);
    }

    /**
     * Hide some cells on the puzzle depending on difficulty.
     *
     * @param array|Cell[] $gameGrid The puzzle grid.
     * @param int $difficulty The difficulty of the puzzle.
     * @return array|Cell[] The ready to play puzzle grid.
     */
    private function setDifficulty($gameGrid, $difficulty)
    {
        $squaresToRemove = $difficulty * self::DIFFICULTY_MODIFIER;

        $availableIndexes = range(0, (9 * 9) - 1);

        do {
            $index = array_splice($availableIndexes, array_rand($availableIndexes), 1)[0];
            $gameGrid[$index]->setPlayerProvided(true);
        } while (--$squaresToRemove > 0);

        return $gameGrid;
    }

    /**
     * Get a game grid from the provided complete grid.
     *
     * @param array|int[] $completedGrid A puzzle in its finished state.
     * @return array|Cell[] Get the game grid; an array of Cell objects.
     */
    private function getGameGrid(array $completedGrid)
    {
        // Convert the completed grid of numbers into a grid of Cell objects.
        $cellGrid = [];

        foreach ($completedGrid as $cellIndex => $cellValue) {
            $cellGrid[] = new Cell($cellIndex, $cellValue);
        }

        return $cellGrid;
    }

    /**
     * Get the seed used to generate this puzzle.
     *
     * @return int|string The seed used to generate this puzzle.
     */
    public function getSeed()
    {
        return $this->seed;
    }

    /**
     * Get the difficulty this puzzle was generated with.
     *
     * @return int The difficulty level.
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * Get the puzzle cell at the provided index.
     *
     * @param int $index The index to get the cell at.
     * @return Cell The cell at the index location.
     */
    public function getCellAt($index)
    {
        return $this->puzzle[$index];
    }

    /**
     * Get the puzzle divided up into rows with 9 cells each.
     *
     * @return array|Cell[][] Return an array of cells broken into chunks grouped by rows.
     */
    public function getPlayRows()
    {
        return array_chunk($this->puzzle, 9);
    }

    /**
     * Get an already completed grid grid.
     *
     * @return array|int[] An array of numbers representing the puzzle grid.
     */
    private function getBaseGrid()
    {
        return [
            1, 2, 3,    4, 5, 6,    7, 8, 9,
            4, 5, 6,    7, 8, 9,    1, 2, 3,
            7, 8, 9,    1, 2, 3,    4, 5, 6,

            9, 1, 2,    3, 4, 5,    6, 7, 8,
            3, 4, 5,    6, 7, 8,    9, 1, 2,
            6, 7, 8,    9, 1, 2,    3, 4, 5,

            8, 9, 1,    2, 3, 4,    5, 6, 7,
            2, 3, 4,    5, 6, 7,    8, 9, 1,
            5, 6, 7,    8, 9, 1,    2, 3, 4,
        ];
    }

    /**
     * Validate that the grid with the player provided values is correct.
     *
     * @param array $playerProvidedAnswers A collection of values provided by the player.
     * @return bool True if validation passed; false otherwise.
     */
    public function validate($playerProvidedAnswers)
    {
        // Apply the answers provided by the user.
        foreach ($playerProvidedAnswers as $index => $playerProvidedAnswer) {
            if (strlen($playerProvidedAnswer)) {
                $this->puzzle[$index]->setPlayerValue($playerProvidedAnswer);
            }
        }

        // Check to see if the grid is valid.
        return $this->validateGrid();
    }

    /**
     * Check that the grid validates correctly.
     *
     * This function will manually check for collisions in all rows, columns, and squares instead of checking against
     * the generated solution - this is because there might be more than one way to solve the puzzle.
     *
     * @return bool True if the puzzle is complete; false otherwise.
     */
    private function validateGrid()
    {
        $isValid = true;

        // Check for conflicts in all rows first, its the fastest check we can do.
        foreach ($this->getPlayRows() as $row) {

            $collection = new Collection($row);
            if (!$collection->isValid()) {
                $isValid = false;
            }
        }

        // Next we check all the columns to see if any contain duplicates.
        $gridColumnCells = [];

        foreach ($this->puzzle as $index => $cell) {
            $gridColumnCells[$index % 9][] = $cell;
        }

        foreach ($gridColumnCells as $columnCells) {
            // Check for the duplicates in each column.
            $collection = new Collection($columnCells);
            if (!$collection->isValid()) {
                $isValid = false;
            }
        }

        // Finally check all 9 of the 3x3 grids for duplicate numbers.
        $gridSquareCells = [];

        foreach ($this->puzzle as $index => $cell) {

            $squareX = floor($index / 3) % 3;
            $squareY = floor($index / 27);

            $gridSquareCells[$squareX . ',' . $squareY][] = $cell;

        }

        foreach ($gridSquareCells as $gridCells) {
            // For for the duplicates in each square.
            $collection = new Collection($gridCells);
            if (!$collection->isValid()) {
                $isValid = false;
            }
        }

        return $isValid;
    }

}
