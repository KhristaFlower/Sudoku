<?php /** @var $puzzle \Kriptonic\Sudoku\Puzzle\Sudoku */ ?>

<?php if ($puzzle->isSolved()): ?>
    You have solved the puzzle!
<?php else: ?>
    You're not finished yet!
<?php endif; ?>

<form action="" method="post">
    <input type="hidden" name="seed" value="<?= $puzzle->getSeed() ?>"/>

    <table>
        <?php foreach ($puzzle->getPlayRows() as $i => $row): ?>
            <tr class="<?= floor($i / 3) % 2 ? 'b' : 'a' ?>">
                <?php /** @var \Kriptonic\Sudoku\Puzzle\Cell $cell */ ?>
                <?php foreach ($row as $j => $cell): ?>
                    <td class="cell <?= floor($j / 3) % 2 ? 'b' : 'a' ?> <?= $cell->hasCollision() && $showErrors ? 'collision' : '' ?>">

                        <?php if ($cell->isPlayerProvided()): ?>

                            <input
                                    type="text"
                                    name="playerInput[<?= $cell->getIndex() ?>]"
                                    class="player-input"
                                    maxlength="1"
                                    onkeypress="return validate(event);"
                                    autocomplete="off"
                                    value="<?= $cell->getPlayerValue() ?>"
                                    data-cell-index="<?= $cell->getIndex() ?>"
                            />

                            <input
                                    type="hidden"
                                    name="playerPencils[<?= $cell->getIndex() ?>]"
                                    id="pencil-input-<?= $cell->getIndex() ?>"
                                    <?php if (isset($playerPencils[$cell->getIndex()])): ?>
                                        value="<?= $playerPencils[$cell->getIndex()] ?>"
                                    <?php endif; ?>
                            />

                            <div class="pencil-grid" id="pencil-grid-<?= $cell->getIndex() ?>">
                                <?php if (isset($playerPencils[$cell->getIndex()])): ?>
                                    <?php foreach (explode(';', $playerPencils[$cell->getIndex()]) as $numCell): ?>
                                        <div class="pencil-square pencil-square-<?= $numCell ?>" id="pencil-cell-<?= $cell->getIndex() ?>-<?= $numCell ?>">
                                            <?= $numCell ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                        <?php else: ?>
                            <b><?= $cell->getValue() ?></b>
                        <?php endif; ?>

                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>

    <fieldset>
        <label for="pencil">Pencil mode</label>
        <input type="checkbox" id="pencil">
    </fieldset>

    <fieldset>
        <label for="seed">Seed</label>
        <input type="text" name="seed" id="seed" value="<?= $puzzle->getSeed() ?>">
        <input type="hidden" name="oldSeed" value="<?= $puzzle->getSeed() ?>"/>
    </fieldset>

    <fieldset>
        <label for="difficulty">Difficulty (Lower is easier)</label>
        <input type="number" min="1" max="7" step="1" name="difficulty" id="difficulty" value="<?= $puzzle->getDifficulty() ?>"/>
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
