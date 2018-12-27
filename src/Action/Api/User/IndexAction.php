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

namespace Ixocreate\Admin\Action\Api\User;

use Doctrine\Common\Collections\Criteria;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\Admin\Response\ApiSuccessResponse;
use Ixocreate\Entity\Entity\EntityInterface;
use Ixocreate\Schema\Listing\ListElement;
use Ixocreate\Schema\Listing\ListSchema;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class IndexAction implements MiddlewareInterface
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
        $criteria = new Criteria();
        $sorting = null;

        $criteria->andWhere(Criteria::expr()->isNull('deletedAt'));

        $schema = (new ListSchema())
            ->withAddedElement(new ListElement('email', 'Email'))
            ->withAddedElement(new ListElement('role', 'Role'));

        //?sort[column1]=ASC&sort[column2]=DESC&filter[column1]=test&filter[column2]=foobar
        $queryParams = $request->getQueryParams();
        foreach ($queryParams as $key => $value) {
            if (\mb_substr($key, 0, 4) === "sort") {
                $sorting = [];
                foreach ($value as $sortName => $sortValue) {
                    if (!$schema->has($sortName)) {
                        continue;
                    }
                    $sorting[$sortName] = $sortValue;
                }
            } elseif ($key === "search" && \is_string($value)) {
                $expr = Criteria::expr();
                $search = [];
                foreach ($schema->elements() as $element) {
                    if (!$element->searchable()) {
                        continue;
                    }
                    $search[] = $expr->contains($element->name(), $value);
                }
                if (!empty($search)) {
                    $or = call_user_func_array([$expr, 'orX'], $search);
                    $criteria->andWhere($or);
                }

                continue;
            } elseif ($key === "offset") {
                $value = (int) $value;
                if (!empty($value)) {
                    $criteria->setFirstResult($value);
                }
                continue;
            } elseif ($key === "limit") {
                $value = (int) $value;
                if (!empty($value)) {
                    $criteria->setMaxResults(\min($value, 500));
                }
                continue;
            }
        }

//        if (empty($sorting) && !empty($resource->listSchema()->defaultSorting())) {
//            $criteria->orderBy([$resource->listSchema()->defaultSorting()['sorting'] => $resource->listSchema()->defaultSorting()['direction']]);
//        } elseif (!empty($sorting)) {
//            $criteria->orderBy($sorting);
//        }

        $result = $this->userRepository->matching($criteria);
        $items = [];
        //TODO Collection
        /** @var EntityInterface $entity */
        foreach ($result as $entity) {
            $items[] = $entity->toPublicArray();
        }

        $count = $this->userRepository->count($criteria);

        return new ApiSuccessResponse(['schema' => $schema,'items' => $items, 'meta' => ['count' => $count]]);
    }
}
