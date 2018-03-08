<?php
namespace KiwiSuite\Admin\Action\Api\User;

use App\Admin\Entity\Tag;
use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\Admin\Resource\ResourceInterface;
use KiwiSuite\Admin\Resource\ResourceSubManager;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\Database\Repository\Factory\RepositorySubManager;
use KiwiSuite\Database\Repository\RepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouteResult;

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
            if (substr($key, 0, 4) === "sort") {
                //filter

                continue;
            }

            if (substr($key, 0, 6) === "filter") {
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
