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

namespace KiwiSuite\Admin\Action\Api\User;

use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EmailAction implements MiddlewareInterface
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //?sortColumn1=ASC&sortColumn2=DESC&filterColumn1=test&filterColumn2=foobar
        $queryParams = $request->getQueryParams();
        foreach ($queryParams as $key => $value) {
            if (\mb_substr($key, 0, 4) === "sort") {
                //filter

                continue;
            }

            if (\mb_substr($key, 0, 6) === "filter") {
                //filter

                continue;
            }

            if ($key === "offset") {
                //offset;

                continue;
            }

            if ($key === "limit") {
                //limit

                continue;
            }
        }

        $result = $this->userRepository->findAll();

        $response = [];
        foreach ($result as $item) {
            $response[] = $item->toPublicArray();
        }

        return new ApiSuccessResponse($response);
    }
}
