<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Permission\Voter;

use Ixocreate\Admin\UserInterface;

interface VoterInterface
{
    /**
     * @param $subject
     * @return bool
     */
    public function supports($subject): bool;

    /**
     * @param UserInterface $user
     * @param $subject
     * @param array $params
     * @return bool
     */
    public function vote(UserInterface $user, $subject, array $params = []): bool;
}
