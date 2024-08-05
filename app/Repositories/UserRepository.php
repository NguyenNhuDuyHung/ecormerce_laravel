<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;

/**
 * Class UserService
 * @package App\Services
 */
class UserRepository implements UserRepositoryInterface
{
    public function __construct()
    {
    }

    public function getAllPaginate()
    {
        return User::paginate(15);
    }
}
