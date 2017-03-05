<?php /** @var $puzzle \Kriptonic\Sudoku\Sudoku */ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sudoku</title>

    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="script.js"></script>

</head>
<body>

    <?php if (isset($solved) ? $solved : false): ?>
        You have solved the puzzle!
    <?php else: ?>
        You're not finished yet!
    <?php endif; ?>

    <form action="" method="post">
        <input type="hidden" name="seed" value="<?php echo $puzzle->getSeed(); ?>"/>

        <table>
            <?php foreach ($puzzle->getPlayRows() as $i => $row): ?>
                <tr class="<?php echo floor($i / 3) % 2 ? 'b' : 'a'; ?>">
                    <?php /** @var \Kriptonic\Sudoku\Cell $cell */ ?>
                    <?php foreach ($row as $j => $cell): ?>
                        <td class="cell <?php echo floor($j / 3) % 2 ? 'b' : 'a'; ?> <?php echo $cell->hasCollision() && $showErrors ? 'collision' : ''; ?>">

                            <?php if ($cell->isPlayerProvided()): ?>

                                <input
                                    type="text"
                                    name="playerInput[<?php echo $cell->getIndex(); ?>]"
                                    class="player-input"
                                    maxlength="1"
                                    onkeypress="return validate(event);"
                                    autocomplete="off"
                                    value="<?php echo $cell->getPlayerValue(); ?>"
                                />

                            <?php else: ?>
                                <b><?php echo $cell->getValue(); ?></b>
                            <?php endif; ?>

                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>

        <fieldset>
            <label for="seed">Seed</label>
            <input type="text" name="seed" id="seed" value="<?php echo $puzzle->getSeed(); ?>">
            <input type="hidden" name="oldSeed" value="<?php echo $puzzle->getSeed(); ?>"/>
        </fieldset>

        <fieldset>
            <label for="difficulty">Difficulty (Lower is easier)</label>
            <input type="number" min="1" max="7" step="1" name="difficulty" id="difficulty" value="<?php echo $puzzle->getDifficulty(); ?>"/>
        </fieldset>

        <fieldset>
            <label for="showErrors">Show errors</label>
            <input type="checkbox" name="showErrors" id="showErrors" <?php if ($showErrors) { echo 'checked'; } ?>/>
        </fieldset>

        <fieldset>
            <input type="submit" value="Generate / Validate"/>
        </fieldset>

    </form>

    <p>
        This project is on <a href="https://github.com/Kriptonic/Sudoku">GitHub</a>!
    </p>

</body>
</html>
