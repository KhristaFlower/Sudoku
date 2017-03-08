<?php

namespace Kriptonic\Sudoku\Puzzle;

/**
 * Class Collection
 *
 * A collection will take an array of cells and figure out if there are conflicts, the conflicting cells are marked so
 * that we can handle it later. Typically this class will be provided with a collection of cells forming one of the
 * check conditions in Sudoku, namely the 9 cells in each row, column, and square.
 *
 * @package Kriptonic\Sudoku\Puzzle
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 */
class Collection
{
    /**
     * The collection of cells that this collection represents.
     * @var array|Cell[] The cells in this collection.
     */
    private $collectionCells = [];

    /**
     * Holds whether or not this collection is valid: has 9 unique cells.
     * @var bool True if the collection is valid; false otherwise.
     */
    private $isValid;

    /**
     * Collection constructor.
     * @param array|Cell[] $cells The cells that form part of this collection.
     */
    public function __construct(array $cells)
    {
        // Map out what cells have which values.
        /** @var Cell[][] $map A map of the cells using a particular value in this collection. */
        $map = [];

        foreach ($cells as $cell) {
            // Cells that need to be provided by the player but haven't been aren't taken into consideration.
            if (!$cell->isPlayerProvided() || $cell->isPlayerProvided() && $cell->getValue()) {
                $map[$cell->getValue()][] = $cell;
            }
        }

        // Have we got 9 distinct values?
        $this->isValid = count($map) === 9;

        if (!$this->isValid) {
            // Mark the duplicate cells as collided, we can display these differently later.
            foreach ($map as $value => $cellsWithValue) {

                if (count($cellsWithValue) === 1) {
                    // Everything is fine with cells using this value.
                    continue;
                }

                // We have duplicates - mark the cells using this value as colliding.
                foreach ($cellsWithValue as $cell) {
                    $cell->setCollision(true);
                }

            }
        }

        $this->collectionCells = $cells;
    }

    /**
     * Get the validity state of this collection.
     *
     * @return bool True if this collection is valid; false otherwise.
     */
    public function isValid()
    {
        return $this->isValid;
    }
}
