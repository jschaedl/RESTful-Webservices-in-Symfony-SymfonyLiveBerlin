<?php

declare(strict_types=1);

namespace App\Pagination;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class PaginatedCollectionFactory
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    abstract public function getRepository(): ServiceEntityRepositoryInterface;

    abstract public function getRouteName(): string;

    public function create(int $page, int $size): PaginatedCollection
    {
        $query = $this->getRepository()
            ->createQueryBuilder('u')
            ->orderBy('u.id', 'asc')
            ->getQuery()
        ;

        $paginator = new Paginator($query);
        $total = count($paginator);
        $pageCount = (int) ceil($total / $size);

        $paginator
            ->getQuery()
            ->setFirstResult($size * ($page - 1))
            ->setMaxResults($size)
        ;

        $paginatedCollection = new PaginatedCollection($paginator->getIterator(), $total);

        $routeName = $this->getRouteName();

        $paginatedCollection
            ->addLink('self', $this->urlGenerator->generate($routeName, ['page' => $page, 'size' => $size]));

        if (1 < $pageCount) {
            $paginatedCollection
                ->addLink('first', $this->urlGenerator->generate($routeName, ['page' => 1, 'size' => $size]))
                ->addLink('last', $this->urlGenerator->generate($routeName, ['page' => $pageCount, 'size' => $size]))
            ;
        }

        if ($page < $pageCount) {
            $paginatedCollection
                ->addLink('next', $this->urlGenerator->generate($routeName, ['page' => $page + 1, 'size' => $size]));
        }

        if ($page > 1) {
            $paginatedCollection
                ->addLink('prev', $this->urlGenerator->generate($routeName, ['page' => $page - 1, 'size' => $size]));
        }

        return $paginatedCollection;
    }
}
