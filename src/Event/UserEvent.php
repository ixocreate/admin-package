<?php

declare(strict_types=1);

namespace KiwiSuite\Admin\Event;

use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Event\Event;

class UserEvent extends Event
{
    public CONST EVENT_CREATE = 'admin-user.create';
    public CONST EVENT_UPDATE = 'admin-user.update';

    /**
     * @var User
     */
    private $user;

    /**
     * UserEvent constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function user(): User
    {
        return $this->user;
    }
}
