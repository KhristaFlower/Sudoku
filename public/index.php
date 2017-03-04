<?php

require '../vendor/autoload.php';

// Load the puzzle options, or defaults if they don't exist.
$seed = isset($_POST['seed']) ? $_POST['seed'] : 1;
$difficulty = isset($_POST['difficulty']) ? $_POST['difficulty'] : 3;

// Generate a new puzzle.
$puzzle = new \Kriptonic\Sudoku\Sudoku($seed, $difficulty);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check the player's answers if they have submit the form.
    $playerProvidedAnswers = $_POST['playerInput'] ?: [];
    $solved = $puzzle->validate($playerProvidedAnswers);
}

include '../views/template.view.php';
