<?php

require '../vendor/autoload.php';

// Load the puzzle options, or defaults if they don't exist.
$seed = isset($_POST['seed']) ? $_POST['seed'] : 1;
$oldSeed = $_POST['oldSeed'];
$difficulty = isset($_POST['difficulty']) ? $_POST['difficulty'] : 3;
$showErrors = isset($_POST['showErrors']) ? $_POST['showErrors'] : false;

// Generate a new puzzle.
$puzzle = new \Kriptonic\Sudoku\Sudoku($seed, $difficulty);

// We must be posting and not generating a new puzzle - We don't want answers from an old puzzle showing in a new one.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $seed === $oldSeed) {
    // Check the player's answers if they have submit the form.
    $playerProvidedAnswers = $_POST['playerInput'] ?: [];
    $solved = $puzzle->validate($playerProvidedAnswers);
}

include '../views/template.view.php';
