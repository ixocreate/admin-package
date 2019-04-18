<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Resource\Widgets;

use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Response\ApiSuccessResponse;
use Ixocreate\Admin\Widget\WidgetCollector;
use Ixocreate\Admin\Resource\WidgetPosition\AboveCreateWidgetInterface;
use Ixocreate\Admin\Resource\WidgetPosition\AboveEditWidgetInterface;
use Ixocreate\Admin\Resource\WidgetPosition\AboveListWidgetInterface;
use Ixocreate\Admin\Resource\WidgetPosition\BelowCreateWidgetInterface;
use Ixocreate\Admin\Resource\WidgetPosition\BelowEditWidgetInterface;
use Ixocreate\Admin\Resource\WidgetPosition\BelowListWidgetInterface;
use Ixocreate\Admin\Resource\Widgets\WidgetsInterface;
use Ixocreate\Resource\ResourceSubManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class WidgetsAction implements MiddlewareInterface
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
        $collector = new WidgetCollector();

        $user = $request->getAttribute(User::class);

        /** @var WidgetsInterface $resource */
        $resource = $this->resourceSubManager->get($request->getAttribute('resource'));

        $position = $request->getAttribute("position");
        $type = $request->getAttribute("type");

        switch ($type) {
            case 'list':
                if ($position === 'above' && $resource instanceof  AboveListWidgetInterface) {
                    $resource->receiveAboveListWidgets($user, $collector);
                }
                if ($position === 'below' && $resource instanceof BelowListWidgetInterface) {
                    $resource->receiveBelowListWidgets($user, $collector);
                }
                break;
            case 'create':
                if ($position === 'above' && $resource instanceof AboveCreateWidgetInterface) {
                    $resource->receiveAboveCreateWidgets($user, $collector);
                }
                if ($position === 'below' && $resource instanceof BelowCreateWidgetInterface) {
                    $resource->receiveBelowCreateWidgets($user, $collector);
                }
                break;
            case 'edit':
                if ($position === 'above' && $resource instanceof AboveEditWidgetInterface) {
                    $resource->receiveAboveEditWidgets($user, $collector, $request->getAttribute('id'));
                }
                if ($position === 'below' && $resource instanceof BelowEditWidgetInterface) {
                    $resource->receiveBelowEditWidgets($user, $collector, $request->getAttribute('id'));
                }
                break;

            default:
                break;
        }

        return new ApiSuccessResponse(['items' => $collector->widgets()]);
    }
}
