<?php
declare(strict_types=1);

namespace CodeShopping\Events;

use CodeShopping\User;

class UserCreatedEvent
{
    private $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

}
