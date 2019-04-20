<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Api\Auth;

use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Response\ApiSuccessResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UserAction implements MiddlewareInterface
{
    /**
     * @var AdminConfig
     */
    private $adminConfig;

    /**
     * UserAction constructor.
     * @param AdminConfig $adminConfig
     */
    public function __construct(AdminConfig $adminConfig)
    {
        $this->adminConfig = $adminConfig;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute(User::class)->toPublicArray();
        $user['permissions'] = $request->getAttribute(User::class)->role()->getRole()->getPermissions();
        $user['locale'] = !empty($user['locale']) ? $user['locale'] : $this->adminConfig->defaultLocale();
        $user['numberLocale'] = !empty($user['numberLocale']) ? $user['numberLocale'] : $this->adminConfig->defaultLocale();
        $user['dateLocale'] = !empty($user['dateLocale']) ? $user['dateLocale'] : $this->adminConfig->defaultLocale();
        $user['timezone'] = !empty($user['timezone']) ? $user['timezone'] : $this->adminConfig->defaultTimezone();
        return new ApiSuccessResponse($user);
    }
}
