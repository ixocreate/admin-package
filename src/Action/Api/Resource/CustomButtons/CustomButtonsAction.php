<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Resource\CustomButtons;

use Ixocreate\Admin\CustomButton\CustomButtonCollector;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Resource\CustomButtonPosition\ListCustomButtonInterface;
use Ixocreate\Admin\Resource\WidgetPosition\EditCustomButtonInterface;
use Ixocreate\Admin\Response\ApiErrorResponse;
use Ixocreate\Admin\Response\ApiSuccessResponse;
use Ixocreate\Resource\ResourceSubManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CustomButtonsAction implements MiddlewareInterface
{
    /**
     * @var ResourceSubManager
     */
    private $resourceSubManager;

    /**
     * WidgetsAction constructor.
     * @param ResourceSubManager $resourceSubManager
     */
    public function __construct(ResourceSubManager $resourceSubManager)
    {
        $this->resourceSubManager = $resourceSubManager;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $collector = new CustomButtonCollector();

        $user = $request->getAttribute(User::class);

        $resource = $this->resourceSubManager->get($request->getAttribute('resource'));

        $type = $request->getAttribute('type');

        if ($type === 'list' && $resource instanceof ListCustomButtonInterface) {
            $resource->receiveAboveListCustomButtons($user, $collector);
        }elseif ($type === 'edit' && $resource instanceof EditCustomButtonInterface) {
            $resource->receiveAboveEditCustomButtons($user, $collector, $request->getAttribute('id'));
        }else{
            return new ApiErrorResponse('*', ['wrong customButton type']);
        }

        return new ApiSuccessResponse(['items' => $collector->customButtons()]);
    }
}
