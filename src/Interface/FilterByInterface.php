<?php

namespace Tbn\QueryBuilderRepositoryGeneratorBundle\Interface;

use Doctrine\ORM\QueryBuilder;

interface FilterByInterface
{
    public static function filterBy(
        QueryBuilder $qb,
        string $columnName,
        string $operator,
        mixed $value,
        ?string $entityName = null,
    ): QueryBuilder;
}
