<?php

namespace Kriptonic\App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @package Kriptonic\App\Models
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 *
 * @property integer $id The users id.
 * @property string $username The users username.
 * @property string $email The users email.
 * @property string $password The users password.
 * @property string $created_at The date and time the user was created.
 * @property string $updated_at The date and time the user was last updated.
 */
class User extends Model
{
    /**
     * @var self A User object representing the currently authenticated user.
     */
    private static $currentUser;

    /**
     * @var string The table this model uses.
     */
    protected $table = 'users';

    /**
     * @var array Fields for mass-assignment.
     */
    protected $fillable = ['username', 'email'];

    /**
     * Get a user object for the currently logged in user.
     *
     * @return User|null A User object if someone is logged in; null otherwise.
     */
    public static function current()
    {
        if (!isset(self::$currentUser) && isset($_SESSION['user_id'])) {
            // Load the User object for the user_id in the session.
            self::$currentUser = User::query()->find($_SESSION['user_id']);
        }

        return self::$currentUser;
    }

}
