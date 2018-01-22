<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @see https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Response;

use Zend\Diactoros\Response\JsonResponse;

final class ApiErrorResponse extends JsonResponse
{
    public function __construct(string $errorCode, array $messages = [], int $status = 400)
    {
        $payload = [
            'success' => false,
            'errorCode' => $errorCode,
            'errorMessages' => $messages,
        ];
        parent::__construct($payload, $status);
    }
}
