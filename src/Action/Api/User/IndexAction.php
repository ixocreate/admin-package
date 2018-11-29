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

namespace KiwiSuite\Admin\Action\Api\User;

use Doctrine\Common\Collections\Criteria;
use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\Entity\Entity\EntityInterface;
use KiwiSuite\Schema\Listing\ListElement;
use KiwiSuite\Schema\Listing\ListSchema;
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

        //?sort[column1]=ASC&sort[column2]=DESC&filter[column1]=test&filter[column2]=foobar
        $queryParams = $request->getQueryParams();
        foreach ($queryParams as $key => $value) {
            if (\mb_substr($key, 0, 4) === "sort") {
//                $sorting = [];
//                foreach ($value as $sortName => $sortValue) {
//                    if (!$listSchema->has($sortName)) {
//                        continue;
//                    }
//                    $sorting[$sortName] = $sortValue;
//                }
//            } elseif (\mb_substr($key, 0, 6) === "filter") {
//                foreach ($value as $filterName => $filterValue) {
//                    if (!$listSchema->has($filterName)) {
//                        continue;
//                    }
//                    $criteria->andWhere(Criteria::expr()->contains($filterName, $filterValue));
//                }
//                continue;
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

        $count = $this->userRepository->count(['deletedAt' => null]);

        $schema = (new ListSchema())
            ->withAddedElement(new ListElement('email', 'Email'))
            ->withAddedElement(new ListElement('role', 'Role'));

        return new ApiSuccessResponse(['schema' => $schema,'items' => $items, 'meta' => ['count' => $count]]);
    }
}
