<?php

namespace Tbn\QueryBuilderRepositoryGeneratorBundle\Interface;

use Doctrine\ORM\QueryBuilder;

interface FilterInInterface
{
    public static function filterIn(
        QueryBuilder $qb,
        string $columnName,
        string $operator,
        array $values,
        ?string $entityName = null,
    ): QueryBuilder;
}
