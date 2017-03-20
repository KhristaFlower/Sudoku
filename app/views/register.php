<h1>Create an account</h1>

<form action="register" method="post">

    <?php if (isset($errors) && count($errors)): ?>
        <p>
            The following errors were encountered:
        </p>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li>
                    <?= $error ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p>
        * Asterisks indicate required fields.
    </p>

    <fieldset>
        <label for="name">Username*</label>
        <input type="text" name="name" id="name" value="<?= $desiredUsername ?? '' ?>"/>
    </fieldset>

    <fieldset>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= $desiredEmail ?? '' ?>"/>
    </fieldset>

    <fieldset>
        <label for="password">Password*</label>
        <input type="password" name="password" id="password"/>
    </fieldset>

    <fieldset>
        <label for="submit"></label>
        <input type="submit" id="submit" value="Create"/>
    </fieldset>

</form>
