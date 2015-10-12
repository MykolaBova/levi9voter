<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;

class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $username
     * @return User|null
     */
    public function findUserByUsername($username)
    {
        return $this->userRepository->findOneByUsername($username);
    }

    /**
     * Save User entity filled with LDAP data
     *
     * @param User $user
     */
    public function saveLDAPUserData(User $user)
    {
        $this->userRepository->saveUser($user);
    }
}
