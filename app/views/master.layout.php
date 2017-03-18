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

    <p>
        <?php if ($currentUser): ?>
            Logged in as <?= $currentUser->username ?> - <a href="/logout">Logout</a>
        <?php else: ?>
            <a href="/login">Login</a> or <a href="/register">register</a> to track puzzles.
        <?php endif; ?>
    </p>

    <?php require $pageInclude; ?>

</body>
</html>
