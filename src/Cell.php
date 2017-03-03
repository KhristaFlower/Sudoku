<?php

namespace Kriptonic\Sudoku;

/**
 * Class Cell
 *
 * The Cell is responsible for holding information relating to a position on the grid.
 *
 * @package Kriptonic\Sudoku
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 */
class Cell
{
    /**
     * The position of this cell in the grid.
     * @var int
     */
    private $index;

    /**
     * The number of this cell on the grid.
     * @var int
     */
    private $value;

    /**
     * Is this cells value hidden to the player?
     * @var boolean True if the player should provide this value.
     */
    private $isPlayerProvided = false;

    /**
     * The value provided by the player for this cell.
     * @var int
     */
    private $playerValue = null;

    /**
     * Used to hold whether the cell is in an error state.
     * @todo: implementation
     * @var bool True if in an error state; false otherwise.
     */
    private $isErrored = false;

    /**
     * Cell constructor.
     *
     * @param int $index The index this cell represents in the grid.
     * @param int $value The number stored in this cell.
     */
    public function __construct($index, $value)
    {
        $this->index = $index;
        $this->value = $value;
    }

    /**
     * Change whether this cell is player provided.
     *
     * @param bool $newValue Is this cell player provided?
     */
    public function setPlayerProvided($newValue)
    {
        $this->isPlayerProvided = $newValue;
    }

    /**
     * Get the value provided by the player.
     *
     * @return int The value provided.
     */
    public function getPlayerValue()
    {
        if ($this->playerValue !== null) {
            return $this->playerValue;
        }

        return null;
    }

    /**
     * Set the value provided by the player.
     *
     * @param int $playerValue The value the player provided.
     */
    public function setPlayerValue($playerValue)
    {
        if ($playerValue !== null) {
            $playerValue = (int)$playerValue;
        }

        $this->playerValue = $playerValue;
    }

    /**
     * Get the value contained in this cell.
     *
     * @return int The value in this cell.
     */
    public function getValue()
    {
        if ($this->isPlayerProvided()) {
            return $this->playerValue;
        } else {
            return $this->value;
        }
    }

    /**
     * Assign a new value to this cell.
     *
     * @param int $newValue The new value for this cell.
     */
    public function setValue($newValue)
    {
        $this->value = $newValue;
    }

    /**
     * Check if this cell is player provided.
     *
     * @return boolean True if the cell is player provided; false otherwise.
     */
    public function isPlayerProvided()
    {
        return $this->isPlayerProvided;
    }

    /**
     * Get the index this cell represents.
     *
     * @return int The index this cell represents.
     */
    public function getIndex()
    {
        return $this->index;
    }
}
