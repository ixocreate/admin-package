<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Response;

use Laminas\Diactoros\Response\JsonResponse;

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
