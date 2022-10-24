<?php
declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User;
use Symfony\Component\Mime\Email;

interface UserRepositoryInterface
{
    public function save(User $user);

    /**
     * @param String $email
     * @return array
     * (
     * [] => [
     *      ['email'] => string,
     * ]
     * )
     */
    public function countUserName(string $email): int;
}