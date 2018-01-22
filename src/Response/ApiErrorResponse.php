<?php
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
