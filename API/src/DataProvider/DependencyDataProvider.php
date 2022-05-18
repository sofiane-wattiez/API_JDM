<?php

namespace App\Controller\DataProvider;

use Ramsey\Uuid\Uuid;
use App\Entity\Dependency;
use App\Repository\DependencyRepository;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;

class DependencyDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface, ItemDataProviderInterface
{

    private DependencyRepository $repository;

    public function __construct(DependencyRepository $repository)
    {

        $this->repository = $repository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {

        return $resourceClass == Dependency::class;

    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []) 
    {

        return $this->repository->find($id);

    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [] ){
        return $this->repository->findAll();
    }

}