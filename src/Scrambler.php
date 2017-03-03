<?php

namespace Kriptonic\Sudoku;

/**
 * Class Scrambler
 *
 * Used to scramble a Sudoku grid.
 *
 * @package Kriptonic\Sudoku
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 */
class Scrambler
{
    /**
     * The number of operations to perform when scrambling the grid.
     * @const int Number of operations.
     */
    const SCRAMBLE_OPERATIONS = 100;

    /**
     * Operation constants.
     */
    const OPERATION_SWITCH = 'switch';
    const OPERATION_FLIP = 'flip';

    /**
     * Switch type constants.
     */
    const SWITCH_SINGLE = 'single';
    const SWITCH_GROUP = 'group';

    /**
     * Directional constants.
     */
    const DIRECTION_VERTICAL = 'vertical';
    const DIRECTION_HORIZONTAL = 'horizontal';

    /**
     * Flip constants.
     */
    const FLIP_TL_BR = 'top left to bottom right';
    const FLIP_TR_BL = 'top right to bottom left';

    /**
     * The resulting grid after the scrambling process.
     * @var array
     */
    private $scrambledGrid;

    /**
     * Scrambler constructor.
     *
     * @param array $grid The grid to be scrambled.
     */
    public function __construct(array $grid)
    {
        $this->scramble($grid);

        $this->scrambledGrid = $grid;
    }

    /**
     * Get the scrambled grid.
     *
     * @return array|int[]
     */
    public function getGrid()
    {
        return $this->scrambledGrid;
    }

    /**
     * Convert an X and Y coordinate pair into an index for a scalar array.
     *
     * @param int $x The X coordinate.
     * @param int $y The Y coordinate.
     * @return int The scalar index for the provided X and Y coordinates.
     */
    private function getIndexFromXY($x, $y)
    {
        return ($y * 9) + $x;
    }

    /**
     * Takes a grid and scrambles the values.
     *
     * @param array|int[] $grid The grid to be scrambled.
     */
    public function scramble(&$grid)
    {
        $scramblesPerformed = 0;

        while ($scramblesPerformed++ < self::SCRAMBLE_OPERATIONS) {

            // Decide on a scramble operation.
            $switchOrFlip = rand(1, 10) === 1 ? self::OPERATION_FLIP : self::OPERATION_SWITCH;

            if ($switchOrFlip === self::OPERATION_FLIP) {
                // Decide on a flip axis.
                $flipAxis = range(1, 2) === 1 ? self::FLIP_TL_BR : self::FLIP_TR_BL;

                $this->performFlip($grid, $flipAxis);
            } elseif ($switchOrFlip === self::OPERATION_SWITCH) {
                // Decide on whether we switch a pair of singles or groups.
                $singleOrGroup = rand(1, 3) === 1 ? self::SWITCH_GROUP : self::SWITCH_SINGLE;

                // Pick a direction.
                $direction = rand(1, 2) === 1 ? self::DIRECTION_HORIZONTAL : self::DIRECTION_VERTICAL;

                // Pick the indexes to switch.
                list($lowIndex, $highIndex) = $this->getSwitchIndexes($singleOrGroup);

                $dataSize = [
                    self::SWITCH_SINGLE => 1,
                    self::SWITCH_GROUP => 3
                ][$singleOrGroup];

                if ($direction === self::DIRECTION_HORIZONTAL) {
                    // For horizontal switches we multiply by 9 to get the number of rows instead of columns.
                    $this->performHorizontalSwitch($grid, $lowIndex, $highIndex, $dataSize * 9);
                } elseif ($direction === self::DIRECTION_VERTICAL) {
                    $this->performVerticalSwitch($grid, $lowIndex, $highIndex, $dataSize);
                } else {
                    throw new \InvalidArgumentException('Unsupported orientation ' . $direction);
                }

            }

        }
    }

    /**
     * Perform a board flip.
     *
     * @param array|int[] $grid The board to be flipped.
     * @param string $orientation The direction of the flip.
     */
    public function performFlip(&$grid, $orientation)
    {
        // We need to keep track as we will have switched all elements half way through, which we then swap back.
        $switchesPerformed = [];

        foreach (range(0, 8) as $x) {
            foreach (range(0, 8) as $y) {

                // If the flip line is from top left to bottom right then a simple reverse of X and Y will suffice.
                // Otherwise we need to deduct one dimension from the maximum of the other to get the opposite.

                if ($orientation === self::FLIP_TR_BL) {
                    /*
                     * By taking the opposite dimension from the max, we translate the coordinate.
                     *   0 1 2 3
                     * 0 - A - /
                     * 1 - - / -
                     * 2 - / - B
                     * 3 / - - -
                     *
                     * The coordinates 1,0 translate to 3,2 by doing the following:
                     * $x2 = 3 - $y; // 3 - 0
                     * $y2 = 3 - $x; // 3 - 1
                     */
                    $x2 = 8 - $y;
                    $y2 = 8 - $x;
                } elseif ($orientation === self::FLIP_TL_BR) {
                    // Nice and easy X, Y switch.
                    $x2 = $y;
                    $y2 = $x;
                } else {
                    throw new \InvalidArgumentException('Unsupported orientation ' . $orientation);
                }

                // Skip any cells that have been switched already.
                if (isset($switchesPerformed[min($x, $y)][max($x, $y)])) {
                    continue;
                }

                // Save the opposite cell as we don't want to swap that one when we reach it (it'll undo the swap).
                $switchesPerformed[min($x2, $y2)][max($x2, $y2)] = true;

                $this->performFlipSwitch($grid, $x, $y, $x2, $y2);

            }
        }
    }

    /**
     * Perform a cell switch.
     *
     * @param array|int[] $grid The grid to modify.
     * @param int $x1 The x coordinate for the first cell to swap.
     * @param int $y1 The y coordinate for the first cell to swap.
     * @param int $x2 The x coordinate for the second cell to swap.
     * @param int $y2 The y coordinate for the second cell to swap.
     */
    public function performFlipSwitch(&$grid, $x1, $y1, $x2, $y2)
    {
        $index1 = $this->getIndexFromXY($x1, $y1);
        $index2 = $this->getIndexFromXY($x2, $y2);

        $value1 = $grid[$index1];

        $grid[$index1] = $grid[$index2];
        $grid[$index2] = $value1;
    }

    /**
     * Perform a row switch.
     *
     * @param array|int[] $grid The grid to modify.
     * @param int $lowStart The lower row index to switch.
     * @param int $highStart The higher row index to switch.
     * @param int $length The width of the rows to switch.
     */
    private function performHorizontalSwitch(&$grid, $lowStart, $highStart, $length)
    {
        // Take out the data we need, highest index first (else the other data will be off by one).
        $highData = array_splice($grid, $highStart * $length, $length);
        $lowData = array_splice($grid, $lowStart * $length, $length);

        // Put the extracted data back, lowest index first (so the high index is back in the right place).
        array_splice($grid, $lowStart * $length, 0, $highData);
        array_splice($grid, $highStart * $length, 0, $lowData);
    }

    /**
     * Perform a column switch.
     *
     * @param array|int[] $grid The grid to modify.
     * @param int $lowOffset The lower column index to switch.
     * @param int $highOffset The higher column index to switch.
     * @param int $length The width of the columns to switch.
     */
    private function performVerticalSwitch(&$grid, $lowOffset, $highOffset, $length)
    {
        // Unlike the horizontal switcher we have to perform our switches on a per-row level.
        // We perform a similar operation but it takes a lot more splices to achieve it.
        foreach (range(0, 8) as $rowIndex) {
            $rowOffset = $rowIndex * 9;

            $highStart = $rowOffset + ($highOffset * $length);
            $lowStart = $rowOffset + ($lowOffset * $length);

            // Remove data in one order.
            $highData = array_splice($grid, $highStart, $length);
            $lowData = array_splice($grid, $lowStart, $length);

            // Put the data back in the other order.
            array_splice($grid, $lowStart, 0, $highData);
            array_splice($grid, $highStart, 0, $lowData);
        }
    }

    /**
     * Generates two indexes that are allowed to be switched.
     *
     * @param string $singleOrGroup Do we need indexes for a single or group switch.
     * @return array An array containing the two indexes to switch.
     */
    private function getSwitchIndexes($singleOrGroup)
    {
        $indexes = range(0, 2);

        // We use splices here as they remove the elements from the array and prevent them being picked twice.
        $index1 = array_splice($indexes, array_rand($indexes), 1)[0];
        $index2 = array_splice($indexes, array_rand($indexes), 1)[0];

        if ($singleOrGroup === self::SWITCH_SINGLE) {
            // For a single switch both rows will be restricted to the same group, we randomise that group now.
            $groupOffset = rand(0, 2) * 3;

            $index1 += $groupOffset;
            $index2 += $groupOffset;
        }

        $indexPair = [$index1, $index2];

        // We sort the pair because other places in the scrambler rely switching data in a certain order to work.
        sort($indexPair);

        return $indexPair;
    }
}
