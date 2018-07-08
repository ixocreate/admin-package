<?php
namespace KiwiSuite\Admin\Action\Api\User;

use Doctrine\Common\Collections\Criteria;
use Identicon\Generator\ImageMagickGenerator;
use Identicon\Identicon;
use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\Admin\Response\ApiDetailResponse;
use KiwiSuite\Admin\Response\ApiListResponse;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\ApplicationHttp\Middleware\MiddlewareSubManager;
use KiwiSuite\Contract\Resource\AdminAwareInterface;
use KiwiSuite\Contract\Resource\ResourceInterface;
use KiwiSuite\Database\Repository\Factory\RepositorySubManager;
use KiwiSuite\Database\Repository\RepositoryInterface;
use KiwiSuite\Entity\Entity\EntityInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;

final class CreateAction implements MiddlewareInterface
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AdminAwareInterface $resource */
        $resource = $request->getAttribute(ResourceInterface::class);

        $data = $request->getParsedBody();
        $data['id'] = Uuid::uuid4()->toString();
        $data['hash'] = Uuid::uuid4()->toString();
        $data['createdAt'] = new \DateTime();
        $data['password'] = \password_hash($data['password'], PASSWORD_DEFAULT);

        $identicion = new Identicon(new ImageMagickGenerator());
        $data['avatar'] = $identicion->getImageDataUri($data['email']);

        $entity = new User($data);
        $this->userRepository->save($entity);

        return new ApiSuccessResponse(['id' => $data['id']]);
    }
}
