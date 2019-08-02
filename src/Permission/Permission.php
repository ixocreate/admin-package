<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Permission;

use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Permission\Voter\VoterInterface;
use Ixocreate\Admin\Permission\Voter\VoterSubManager;
use Ixocreate\Admin\RoleInterface;
use Ixocreate\Admin\UserInterface;
use Ixocreate\Admin\VoterProviderInterface;

final class Permission
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var VoterSubManager
     */
    private static $voterSubManager;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @param VoterSubManager $voterSubManager
     */
    public static function initialize(VoterSubManager $voterSubManager): void
    {
        self::$voterSubManager = $voterSubManager;
    }

    public function withUser(UserInterface $user): Permission
    {
        return new Permission($user);
    }

    public function can($subject, array $params = []): bool
    {
        $role = $this->user->getRole();

        if (\is_string($subject)) {
            return $this->evaluateString($role, $subject);
        }

        if (!($role instanceof VoterProviderInterface)) {
            return false;
        }

        $counter = 0;

        foreach ($role->voters() as $voter) {
            /** @var VoterInterface $voter */
            $voter = self::$voterSubManager->get($voter);

            if (!$voter->supports($subject)) {
                continue;
            }

            $counter++;
            if (!$voter->vote($this->user, $subject, $params)) {
                return false;
            }

        }

        if ($counter > 0) {
            return true;
        }

        return false;
    }

    private function evaluateString(RoleInterface $role, string $permission): bool
    {
        if (\in_array($permission, $role->getPermissions())) {
            return true;
        }

        if (\in_array('*', $role->getPermissions())) {
            return true;
        }

        $permissionParts = \explode('.', $permission);

        for ($i = 0; $i < \count($permissionParts); $i++) {
            $checkPermission = [];
            for ($j = 0; $j <= $i; $j++) {
                $checkPermission[] = $permissionParts[$j];
                if (\in_array(\implode('.', $checkPermission), $role->getPermissions())) {
                    return true;
                }
                if (\in_array(\implode('.', $checkPermission) . '.*', $role->getPermissions())) {
                    return true;
                }
            }
        }

        return false;
    }
}
