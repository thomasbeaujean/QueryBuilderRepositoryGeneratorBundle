<?php

namespace Tbn\QueryBuilderRepositoryGeneratorBundle\Interface;

use Doctrine\ORM\QueryBuilder;

interface FilterByInterface
{
    public static function filterBy(
        QueryBuilder $qb,
        string $columnName,
        string $operator,
        string $value,
        ?string $entityName = null,
    ): QueryBuilder;
}
