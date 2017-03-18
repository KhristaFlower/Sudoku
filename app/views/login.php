<h1>Login</h1>

<?php if (isset($message)): ?>
    <p>
        <?= $message ?>
    </p>
<?php endif; ?>

<form action="/login" method="post">

    <fieldset>
        <label for="username">Username</label>
        <input type="text" name="username" id="username">
    </fieldset>

    <fieldset>
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </fieldset>

    <fieldset>
        <label for="submit"></label>
        <input type="submit" id="submit">
    </fieldset>

</form>
