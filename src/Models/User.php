<?php namespace RescueCore\Models;

use \Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $avatar
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 */

class User extends Model
{
    public static $_table = 'users';
}
