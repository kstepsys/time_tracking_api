<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * stores a new user in the database
     * @param Array $input
     * @return App\User
     */
    public static function createUser(array $userInput)
    {
        $userInput['password'] = Hash::make($userInput['password']);
        
        return User::create($userInput);
    }

    /**
     * checks if correct password entered, rehashes if needed
     * @param String password
     * @return Boolean
     */
    public function passwordValid(?string $password)
    {
        if (!Hash::check($password, $this['password'])) {
            return false;
        }
        if (Hash::needsRehash($this['password'])) {
            $this->password = Hash::make($password);
            $this->save();
        }

        return true;
    }
}
