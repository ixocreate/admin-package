<?php
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
