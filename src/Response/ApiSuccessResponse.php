<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @link https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Response;

use Zend\Diactoros\Response\JsonResponse;

class ApiSuccessResponse extends JsonResponse
{
    public function __construct($data = null, array $notifications = [])
    {
        $payload = [
            'success' => true,
            'notifications' => $notifications,
        ];
        if ($data !== null) {
            $payload['result'] = $data;
        }
        parent::__construct($payload, 200);
    }
}
