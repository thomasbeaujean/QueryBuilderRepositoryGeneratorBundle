<?php

namespace Tbn\QueryBuilderRepositoryGeneratorBundle\Interface;

use Doctrine\ORM\QueryBuilder;

interface QueryBuilderGeneratedInterface
{
    public function findOne(
        mixed $id,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): object;

    public static function filterIn(
        QueryBuilder $qb,
        string $columnName,
        string $operator,
        array $values,
        ?string $entityName = null,
    ): QueryBuilder;

    public static function filterBy(
        QueryBuilder $qb,
        string $columnName,
        string $operator,
        mixed $value,
        ?string $entityName = null,
    ): QueryBuilder;

    public function getNewQueryBuilder(): QueryBuilder;

    public function getQueryBuilderCount(
        QueryBuilder $qb,
        string $entityName,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): int;
}
