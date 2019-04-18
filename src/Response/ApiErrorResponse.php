<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Admin\Response;

use Zend\Diactoros\Response\JsonResponse;

final class ApiErrorResponse extends JsonResponse
{
    public function __construct(string $errorCode, array $messages = [], int $status = 200, array $notifications = [])
    {
        $payload = [
            'success' => false,
            'notifications' => $notifications,
            'errorCode' => $errorCode,
            'errorMessages' => $messages,
        ];
        parent::__construct($payload, $status);
    }
}
