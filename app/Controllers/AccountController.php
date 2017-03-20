<?php

namespace Kriptonic\App\Controllers;

use Illuminate\Support\Traits\CapsuleManagerTrait;
use Kriptonic\App\Core\Request;
use Kriptonic\App\Models\User;

/**
 * Class AccountController
 *
 * This controller is used for account related activities.
 *
 * @package Kriptonic\App\Controllers
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 */
class AccountController
{
    /**
     * Show the register page.
     *
     * @return \Kriptonic\App\Core\Response|\Kriptonic\App\Core\View
     */
    public function register()
    {
        return view('register');
    }

    /**
     * Create a new user account.
     *
     * @return \Kriptonic\App\Core\Redirect|\Kriptonic\App\Core\Response
     */
    public function store()
    {
        // TODO: Move validation out to a separate process.
        $desiredUsername = Request::input('name');
        $desiredPassword = Request::input('password');
        $desiredEmail = Request::input('email');

        $errors = [];

        // TODO: Proper flash system for errors to display on the next page..

        // Validate that we have the required fields.
        if (!$desiredUsername) {
            $errors[] = 'A username is required.';
        } elseif (!preg_match('/[A-Za-z0-9\-_]/', $desiredUsername)) {
            $errors[] = 'Username contains invalid characters (alphanumeric, hyphens, and underscores only)';
        } elseif (User::query()->where('username', '=', $desiredUsername)->exists()) {
            $errors[] = 'Username has been taken';
        }

        if (!$desiredEmail) {
            // No email is valid - if one is provided then we have to validate it.
        } elseif (!filter_var($desiredEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email is not valid';
        } elseif (User::query()->where('email', '=', $desiredEmail)->exists()) {
            $errors[] = 'Email has already been taken';
        }

        if (!$desiredPassword) {
            $errors[] = 'A password is required.';
        }

        if (count($errors)) {
            return view('register', compact('errors', 'desiredUsername', 'desiredEmail'));
        }

        $user = new User();
        $user->username = $desiredUsername;
        $user->email = $desiredEmail;
        $user->password = password_hash($desiredPassword, PASSWORD_BCRYPT);
        $user->save();

        return redirect('login');
    }

    /**
     * Show the login page.
     *
     * @return \Kriptonic\App\Core\Response|\Kriptonic\App\Core\View
     */
    public function login()
    {
        return view('login');
    }

    /**
     * Verify a user's credentials and log them in if they match.
     *
     * @return \Kriptonic\App\Core\Redirect|\Kriptonic\App\Core\Response|\Kriptonic\App\Core\View
     */
    public function doLogin()
    {
        /** @var User $user */
        $user = User::query()
            ->where('username', '=', Request::input('username'))
            ->first();

        // Verify that the passwords match.
        if (!$user || !password_verify(Request::input('password'), $user->password)) {
            // TODO: Use a proper flash system so we can redirect back with the errors.
            $message = 'Account details incorrect.';

            return view('login', compact('message'));
        }

        $_SESSION['user_id'] = $user->id;

        // Back to the homepage.
        return redirect('');
    }

    /**
     * Log the user out.
     *
     * @return \Kriptonic\App\Core\Redirect|\Kriptonic\App\Core\Response
     */
    public function logout()
    {
        session_destroy();

        return redirect('');
    }
}
