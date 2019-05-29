<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Event;

use Ixocreate\Admin\Entity\User;
use Ixocreate\Event\Event;

class UserEvent extends Event
{
    public const EVENT_CREATE = 'admin-user.create';

    public const EVENT_UPDATE = 'admin-user.update';

    public const EVENT_CHANGE_PASSWORD = 'admin-user.change-password';

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
