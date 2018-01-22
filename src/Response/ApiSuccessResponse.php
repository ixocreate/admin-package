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

final class ApiSuccessResponse extends JsonResponse
{
    public function __construct($data = null)
    {
        $payload = [
            'success' => true,
        ];
        if ($data !== null) {
            $payload['result'] = $data;
        }
        parent::__construct($payload, 200);
    }
}
