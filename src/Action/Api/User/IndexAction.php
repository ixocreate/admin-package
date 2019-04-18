<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Action\Api\User;

use Doctrine\Common\Collections\Criteria;
use Ixocreate\Admin\Package\Repository\UserRepository;
use Ixocreate\Admin\Package\Response\ApiSuccessResponse;
use Ixocreate\Schema\Package\ElementInterface;
use Ixocreate\Entity\Package\Entity\EntityInterface;
use Ixocreate\Schema\Package\Listing\DateTimeElement;
use Ixocreate\Schema\Package\Listing\ListSchema;
use Ixocreate\Schema\Package\Listing\TextElement;
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
        $listSchema = (new ListSchema())
            ->withAddedElement(new TextElement('email', 'Email'))
            ->withAddedElement(new TextElement('role', 'Role'))
            ->withAddedElement(new DateTimeElement('createdAt', 'Created', true, false))
            ->withAddedElement(new DateTimeElement('updatedAt', 'Updated', true, false))
            ->withAddedElement(new DateTimeElement('lastLoginAt', 'Last Login', true, false))
            ->withAddedElement(new DateTimeElement('lastActivityAt', 'Last Activity', true, false))
            ->withDefaultSorting('createdAt', 'desc');

        $criteria = new Criteria();

        $criteria->andWhere(Criteria::expr()->isNull('deletedAt'));

        //?sort[column1]=ASC&sort[column2]=DESC&filter[column1]=test&filter[column2]=foobar
        $queryParams = $request->getQueryParams();
        $sorting = [];
        $filterExpressions = [];
        foreach ($queryParams as $key => $value) {
            /**
             * TODO: why not use $key === 'sort' and $key === 'filter'? legacy code depending on it? -> TBD
             */
            if ($key === 'orderBy') {
                $sorting[$value] = $queryParams['orderDirection'] ?? 'asc';
            } elseif ($key === 'orderDirection') {
                // see orderBy
            } elseif (\mb_substr($key, 0, 4) === "sort") {
                foreach ($value as $sortName => $sortValue) {
                    if (!$listSchema->has($sortName)) {
                        continue;
                    }
                    $sorting[$sortName] = $sortValue;
                }
            } elseif (\mb_substr($key, 0, 6) === "filter") {
                foreach ($value as $filterName => $filterValue) {
                    if (!\is_string($filterValue)) {
                        continue;
                    }
                    if (!$listSchema->has($filterName)) {
                        continue;
                    }
                    /** @var ElementInterface $element */
                    $element = $listSchema->elements()[$filterName];
                    if (!$element->searchable()) {
                        continue;
                    }
                    $filterExpressions[] = $criteria::expr()->contains($element->name(), $filterValue);
                }
            } elseif ($key === "search" && \is_string($value)) {
                $expr = Criteria::expr();
                $search = [];
                foreach ($listSchema->elements() as $element) {
                    if (!$element->searchable()) {
                        continue;
                    }
                    $search[] = $expr->contains($element->name(), $value);
                }
                if (!empty($search)) {
                    $or = \call_user_func_array([$expr, 'orX'], $search);
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

        /**
         * apply collected filters
         */
        if (!empty($filterExpressions)) {
            $criteria->andWhere(Criteria::expr()->andX(...$filterExpressions));
        }

        /**
         * apply collected sorts
         */
        if (empty($sorting) && !empty($listSchema->defaultSorting())) {
            $criteria->orderBy([$listSchema->defaultSorting()['sorting'] => $listSchema->defaultSorting()['direction']]);
        } elseif (!empty($sorting)) {
            $criteria->orderBy($sorting);
        }

        $result = $this->userRepository->matching($criteria);
        $items = [];
        //TODO Collection
        /** @var EntityInterface $entity */
        foreach ($result as $entity) {
            $items[] = $entity->toPublicArray();
        }

        $count = $this->userRepository->count($criteria);

        return new ApiSuccessResponse(['schema' => $listSchema,'items' => $items, 'meta' => ['count' => $count]]);
    }
}
