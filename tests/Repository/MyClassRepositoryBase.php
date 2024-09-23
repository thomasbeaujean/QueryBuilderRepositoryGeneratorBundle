<?php

/**
 * File generated by the QueryBuilderRepositoryGeneratorBundle
 * DO NOT MODIFY IT, CHANGES WOULD BE OVERWRITTEN
 */
namespace Tbn\QueryBuilderRepositoryGeneratorBundle\Tests\Repository;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Generated class for filter query builders
 *
 */
class MyClassRepositoryBase extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository
{
    protected static $parameterIndex = 0;

    public function __construct(
        protected CacheInterface $appCache,
        ManagerRegistry $registry,
        string $entity = \Tbn\QueryBuilderRepositoryGeneratorBundle\Tests\Entity\MyClass::class,
    ) {
        parent::__construct($registry, $entity);
    }

    public static function getName(): string
    {
        return 'myClass';
    }

    public static function getParameterIndex(): string
    {
        return 'myClass'.static::$parameterIndex++;
    }

    public function getNewQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('myClass');
    }

    private function getCachedResult(
        QueryBuilder $qb,
        string $methodName,
        string|int|null $hydrationMode = null,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): mixed {
        $query = $qb->getQuery();

        if (!method_exists($query, $methodName)) {
            throw new \Exception("The methode $methodName of QueryBuilder does not exist");
        }

        if (!$useQueryCache) {
            if ($hydrationMode) {
                return $query->$methodName($hydrationMode);
            }
            return $query->$methodName();
        }

        if (null === $cacheId) {
            throw new \Exception('The cacheId must be provided to use useQueryCache');
        }

        return $this->appCache->get($cacheId, function (ItemInterface $item) use ($query, $methodName, $hydrationMode, $resultCacheTags): mixed {
            foreach ($resultCacheTags as $cacheTag) {
                $item->tag($cacheTag);
            }

            if ($hydrationMode) {
                return $query->$methodName($hydrationMode);
            }
            return $query->$methodName();
        });
    }

    public function getQueryBuilderResult(
        QueryBuilder $qb,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ) {
        return $this->getCachedResult(
            qb: $qb,
            methodName: 'getResult',
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function getQueryBuilderSingleResult(
        QueryBuilder $qb,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ) {
        return $this->getCachedResult(
            qb: $qb,
            methodName: 'getSingleResult',
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function getQueryBuilderOneOrNullResult(
        QueryBuilder $qb,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ) {
        return $this->getCachedResult(
            qb: $qb,
            methodName: 'getOneOrNullResult',
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function getQueryBuilderArrayResult(
        QueryBuilder $qb,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ) {
        return $this->getCachedResult(
            qb: $qb,
            methodName: 'getArrayResult',
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function getQueryBuilderSingleArrayResult(
        QueryBuilder $qb,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ) {
        return $this->getCachedResult(
            qb: $qb,
            methodName: 'getSingleResult',
            hydrationMode: Query::HYDRATE_ARRAY,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function getQueryBuilderScalarResult(
        QueryBuilder $qb,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ) {
        return $this->getCachedResult(
            qb: $qb,
            methodName: 'getScalarResult',
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function getQueryBuilderSingleScalarResult(
        QueryBuilder $qb,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ) {
        return $this->getCachedResult(
            qb: $qb,
            methodName: 'getSingleScalarResult',
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function getQueryBuilderCount(
        QueryBuilder $qb,
        $entityName = 'myClass',
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): int {
        $qb->select('count('.$entityName.') as total');
        $total = $this->getQueryBuilderSingleArrayResult(
            qb: $qb,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );

        return $total['total'];
    }

    public function exists(
        $entity,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): bool {
        if (null === $entity) {
            throw new \LogicException('The entity parameter must be provided, it can not be null');
        }

        $exists = false;

        $qb = $this->getNewQueryBuilder();
        $entityId = $entity->getId();
        static::filterById($qb, $entityId);

        $result = static::getQueryBuilderOneOrNullResult(
            qb: $qb,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );

        if (null !==  $result) {
            //the entity was found
            $exists = true;
        }

        return $exists;
    }

    public function getDeleteQueryBuilder(
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('myClass');
        $qb->delete($this->getEntityName(), 'myClass');

        return $qb;
    }

    public function existsByQueryBuilder(
        QueryBuilder $qb,
        string $columnName = 'id',
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): bool {
        $exists = false;
        $qb->select($qb->expr()->count('myClass.'.$columnName));

        $count = $this->getQueryBuilderSingleScalarResult(
            qb: $qb,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );

        if ($count > 0) {
            $exists = true;
        }

        return $exists;
    }

    public function findOne(
        $id,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): \Tbn\QueryBuilderRepositoryGeneratorBundle\Tests\Entity\MyClass {
        // try to extract the value from an enum
        if (is_object($id)) {
            $ref = new \ReflectionClass($id);
            if ($ref->isEnum()) {
                $id = $id->value;
            }
        }
        $qb = $this->getNewQueryBuilder();
        static::filterById($qb, $id);

        try {
            $entity = static::getQueryBuilderSingleResult(
                qb: $qb,
                useQueryCache: $useQueryCache,
                cacheId: $cacheId,
                resultCacheTags: $resultCacheTags,
            );
        } catch (EntityNotFoundException|NoResultException $ex) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(\Tbn\QueryBuilderRepositoryGeneratorBundle\Tests\Entity\MyClass::class, [$id]);
        }

        return $entity;
    }

    public static function filterById(
        QueryBuilder $qb,
        $value,
        $operator = Comparison::EQ,
        $entityName = 'myClass',
        $columnName = 'id',
    ): QueryBuilder {
        if ($value === null) {
            if (Comparison::NEQ === $operator) {
                $qb->andWhere($entityName.'.'.$columnName.' IS NOT NULL');
            } else {
                $qb->andWhere($entityName.'.'.$columnName.' IS NULL');
            }
        } else {
            //get a uniq index
            $index = static::getParameterIndex();
            $parameterName = $columnName.$index;

            $qb->andWhere($entityName.'.'.$columnName.$operator.':'.$parameterName);
            $qb->setParameter($parameterName, $value);
        }

        return $qb;
    }

    public static function filterInId(
        QueryBuilder $qb,
        $value,
        $entityName = 'myClass',
        $columnName = 'id',
    ): QueryBuilder {
        if ($value === null) {
            $qb->andWhere($entityName.'.'.$columnName.' IS NULL');
        } else {
            //get a uniq index
            $index = static::getParameterIndex();
            $parameterName = $columnName.$index;
            $qb->andWhere($entityName.'.'.$columnName.' IN (:'.$parameterName.')');
            $qb->setParameter($parameterName, $value);
        }

        return $qb;
    }

    public static function filterNotInId(
        QueryBuilder $qb,
        $value,
        $entityName = 'myClass',
        $columnName = 'id',
    ): QueryBuilder {
        if ($value === null) {
            $qb->andWhere($entityName.'.'.$columnName.' IS NOT NULL');
        } else {
            //get a uniq index
            $index = static::getParameterIndex();
            $parameterName = $columnName.$index;

            $qb->andWhere($entityName.'.'.$columnName.' NOT IN (:'.$parameterName.')');
            $qb->setParameter($parameterName, $value);
        }

        return $qb;
    }

    public function findById(
        mixed $value,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): array {
        $qb = $this->getNewQueryBuilder();
        static::filterById($qb, $value);

        return $this->getQueryBuilderResult(
            qb: $qb,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function findOneById(
        mixed $value,
        bool $allowNull = false,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): ?\Tbn\QueryBuilderRepositoryGeneratorBundle\Tests\Entity\MyClass {
        $qb = $this->getNewQueryBuilder();
        static::filterById($qb, $value);

        if ($allowNull) {
            return $this->getQueryBuilderOneOrNullResult(
                qb: $qb,
                useQueryCache: $useQueryCache,
                cacheId: $cacheId,
                resultCacheTags: $resultCacheTags,
            );
        }

        return $this->getQueryBuilderSingleResult(
            qb: $qb,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function existsById(
        mixed $value,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): bool {
        $qb = $this->getNewQueryBuilder();
        static::filterById($qb, $value);

        return static::existsByQueryBuilder(
            qb: $qb,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function deleteById(
        mixed $value,
    ): void
    {
        $qb = $this->getDeleteQueryBuilder();
        static::filterById($qb, $value);

        static::getQueryBuilderResult($qb);
    }

    public static function filterByNumber(
        QueryBuilder $qb,
        $value,
        $operator = Comparison::EQ,
        $entityName = 'myClass',
        $columnName = 'number',
    ): QueryBuilder {
        if ($value === null) {
            if (Comparison::NEQ === $operator) {
                $qb->andWhere($entityName.'.'.$columnName.' IS NOT NULL');
            } else {
                $qb->andWhere($entityName.'.'.$columnName.' IS NULL');
            }
        } else {
            //get a uniq index
            $index = static::getParameterIndex();
            $parameterName = $columnName.$index;

            $qb->andWhere($entityName.'.'.$columnName.$operator.':'.$parameterName);
            $qb->setParameter($parameterName, $value);
        }

        return $qb;
    }

    public static function filterInNumber(
        QueryBuilder $qb,
        $value,
        $entityName = 'myClass',
        $columnName = 'number',
    ): QueryBuilder {
        if ($value === null) {
            $qb->andWhere($entityName.'.'.$columnName.' IS NULL');
        } else {
            //get a uniq index
            $index = static::getParameterIndex();
            $parameterName = $columnName.$index;
            $qb->andWhere($entityName.'.'.$columnName.' IN (:'.$parameterName.')');
            $qb->setParameter($parameterName, $value);
        }

        return $qb;
    }

    public static function filterNotInNumber(
        QueryBuilder $qb,
        $value,
        $entityName = 'myClass',
        $columnName = 'number',
    ): QueryBuilder {
        if ($value === null) {
            $qb->andWhere($entityName.'.'.$columnName.' IS NOT NULL');
        } else {
            //get a uniq index
            $index = static::getParameterIndex();
            $parameterName = $columnName.$index;

            $qb->andWhere($entityName.'.'.$columnName.' NOT IN (:'.$parameterName.')');
            $qb->setParameter($parameterName, $value);
        }

        return $qb;
    }

    public function findByNumber(
        mixed $value,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): array {
        $qb = $this->getNewQueryBuilder();
        static::filterByNumber($qb, $value);

        return $this->getQueryBuilderResult(
            qb: $qb,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function findOneByNumber(
        mixed $value,
        bool $allowNull = false,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): ?\Tbn\QueryBuilderRepositoryGeneratorBundle\Tests\Entity\MyClass {
        $qb = $this->getNewQueryBuilder();
        static::filterByNumber($qb, $value);

        if ($allowNull) {
            return $this->getQueryBuilderOneOrNullResult(
                qb: $qb,
                useQueryCache: $useQueryCache,
                cacheId: $cacheId,
                resultCacheTags: $resultCacheTags,
            );
        }

        return $this->getQueryBuilderSingleResult(
            qb: $qb,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function existsByNumber(
        mixed $value,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): bool {
        $qb = $this->getNewQueryBuilder();
        static::filterByNumber($qb, $value);

        return static::existsByQueryBuilder(
            qb: $qb,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function deleteByNumber(
        mixed $value,
    ): void
    {
        $qb = $this->getDeleteQueryBuilder();
        static::filterByNumber($qb, $value);

        static::getQueryBuilderResult($qb);
    }

    public static function filterByName(
        QueryBuilder $qb,
        $value,
        $operator = Comparison::EQ,
        $entityName = 'myClass',
        $columnName = 'name',
    ): QueryBuilder {
        if ($value === null) {
            if (Comparison::NEQ === $operator) {
                $qb->andWhere($entityName.'.'.$columnName.' IS NOT NULL');
            } else {
                $qb->andWhere($entityName.'.'.$columnName.' IS NULL');
            }
        } else {
            //get a uniq index
            $index = static::getParameterIndex();
            $parameterName = $columnName.$index;

            $qb->andWhere($entityName.'.'.$columnName.$operator.':'.$parameterName);
            $qb->setParameter($parameterName, $value);
        }

        return $qb;
    }

    public static function filterInName(
        QueryBuilder $qb,
        $value,
        $entityName = 'myClass',
        $columnName = 'name',
    ): QueryBuilder {
        if ($value === null) {
            $qb->andWhere($entityName.'.'.$columnName.' IS NULL');
        } else {
            //get a uniq index
            $index = static::getParameterIndex();
            $parameterName = $columnName.$index;
            $qb->andWhere($entityName.'.'.$columnName.' IN (:'.$parameterName.')');
            $qb->setParameter($parameterName, $value);
        }

        return $qb;
    }

    public static function filterNotInName(
        QueryBuilder $qb,
        $value,
        $entityName = 'myClass',
        $columnName = 'name',
    ): QueryBuilder {
        if ($value === null) {
            $qb->andWhere($entityName.'.'.$columnName.' IS NOT NULL');
        } else {
            //get a uniq index
            $index = static::getParameterIndex();
            $parameterName = $columnName.$index;

            $qb->andWhere($entityName.'.'.$columnName.' NOT IN (:'.$parameterName.')');
            $qb->setParameter($parameterName, $value);
        }

        return $qb;
    }

    public function findByName(
        mixed $value,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): array {
        $qb = $this->getNewQueryBuilder();
        static::filterByName($qb, $value);

        return $this->getQueryBuilderResult(
            qb: $qb,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function findOneByName(
        mixed $value,
        bool $allowNull = false,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): ?\Tbn\QueryBuilderRepositoryGeneratorBundle\Tests\Entity\MyClass {
        $qb = $this->getNewQueryBuilder();
        static::filterByName($qb, $value);

        if ($allowNull) {
            return $this->getQueryBuilderOneOrNullResult(
                qb: $qb,
                useQueryCache: $useQueryCache,
                cacheId: $cacheId,
                resultCacheTags: $resultCacheTags,
            );
        }

        return $this->getQueryBuilderSingleResult(
            qb: $qb,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function existsByName(
        mixed $value,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): bool {
        $qb = $this->getNewQueryBuilder();
        static::filterByName($qb, $value);

        return static::existsByQueryBuilder(
            qb: $qb,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function deleteByName(
        mixed $value,
    ): void
    {
        $qb = $this->getDeleteQueryBuilder();
        static::filterByName($qb, $value);

        static::getQueryBuilderResult($qb);
    }

    public static function filterByForeignClasses(
        QueryBuilder $qb,
        $value,
        $operator = Comparison::EQ,
        $entityName = 'myClass',
        $columnName = 'foreignClasses',
    ): QueryBuilder {
        //get a uniq index
        $index = static::getParameterIndex();
        $parameterName = $columnName.$index;

        unset($index);

        if ($value === null) {
            if (Comparison::NEQ === $operator) {
                $qb->andWhere($entityName.'.'.$columnName.' IS NOT NULL');
            } else {
                $qb->andWhere($entityName.'.'.$columnName.' IS NULL');
            }
        } else {
            $qb->andWhere($entityName.'.'.$columnName.$operator.':'.$parameterName);

            // by default we use the value
            $id = $value;

            // value might be an enum
            if (is_object($value)) {
                $ref = new \ReflectionClass($value);
                if ($ref->isEnum()) {
                    $value = $value->value;
                }
            }

            if ($value instanceof \Tbn\QueryBuilderRepositoryGeneratorBundle\Tests\Entity\ForeignClass) {
                $id = $value->getId();
            }
            $qb->setParameter($parameterName, $id);
        }

        return $qb;
    }

    public static function filterInForeignClasses(
        QueryBuilder $qb,
        $values,
        $entityName = 'myClass',
        $columnName = 'foreignClasses',
    ): QueryBuilder {
        //get a uniq index
        $index = static::getParameterIndex();
        $parameterName = $columnName.$index;

        unset($index);

        if ($values === null) {
            $qb->andWhere($entityName.'.'.$columnName.' IS NULL');
        } else {
            $ids = array();
            //the array might contains a null value
            $orNull = '';

            foreach ($values as $value) {
                if ($value !== null) {
                    // by default we use the value
                    $id = $value;
                    if ($value instanceof \Tbn\QueryBuilderRepositoryGeneratorBundle\Tests\Entity\ForeignClass) {
                        $id = $value->getId();
                    }

                    $ids[] = $id;
                } else {
                    $orNull = ' OR '.$entityName.'.'.$columnName.' IS NULL';
                }
            }

            $qb->andWhere($entityName.'.'.$columnName.' IN (:'.$parameterName.')'.$orNull);
            $qb->setParameter($parameterName, $ids);
        }

        return $qb;
    }

    public static function filterNotInForeignClasses(
        QueryBuilder $qb,
        $values,
        $entityName = 'myClass',
        $columnName = 'foreignClasses',
    ): QueryBuilder {
        //get a uniq index
        $index = static::getParameterIndex();
        $parameterName = $columnName.$index;

        unset($index);

        if ($values === null) {
            $qb->andWhere($entityName.'.'.$columnName.' IS NOT NULL');
        } else {
            $ids = array();

            foreach ($values as $value) {
                $ids[] = $value->getId();
            }
            $qb->andWhere($entityName.'.'.$columnName.' NOT IN (:'.$parameterName.')');
            $qb->setParameter($parameterName, $ids);
        }

        return $qb;
    }

    public static function joinForeignClasses(
        QueryBuilder $qb,
        $entityName = 'myClass',
        $columnName = 'foreignClasses',
        $entityDqlTargeted = 'foreignClass',
    ): QueryBuilder {
        $qb->join($entityName.'.'.$columnName, $entityDqlTargeted);

        return $qb;
    }

    public static function leftJoinForeignClasses(
        QueryBuilder $qb,
        $entityName = 'myClass',
        $columnName = 'foreignClasses',
        $entityDqlTargeted = 'foreignClass',
    ): QueryBuilder {
        $qb->leftJoin($entityName.'.'.$columnName, $entityDqlTargeted);

        return $qb;
    }

    public function findByForeignClasses(
        mixed $value,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): array {
        $qb = $this->getNewQueryBuilder();
        static::filterByForeignClasses($qb, $value);

        return $this->getQueryBuilderResult(
            qb: $qb,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function findOneByForeignClasses(
        mixed $value,
        bool $allowNull = false,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): ?\Tbn\QueryBuilderRepositoryGeneratorBundle\Tests\Entity\MyClass {
        $qb = $this->getNewQueryBuilder();
        static::filterByForeignClasses($qb, $value);

        if ($allowNull) {
            return $this->getQueryBuilderOneOrNullResult(
                qb: $qb,
                useQueryCache: $useQueryCache,
                cacheId: $cacheId,
                resultCacheTags: $resultCacheTags,
            );
        }

        return $this->getQueryBuilderSingleResult(
            qb: $qb,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }

    public function deleteByForeignClasses(
        mixed $value,
    ): void {
        $qb = $this->getDeleteQueryBuilder();
        static::filterByForeignClasses($qb, $value);

        static::getQueryBuilderResult($qb);
    }

    public function existsByForeignClasses(
        mixed $value,
        bool $useQueryCache = false,
        ?string $cacheId = null,
        array $resultCacheTags = [],
    ): bool {
        $qb = $this->getNewQueryBuilder();
        static::filterByForeignClasses($qb, $value);

        return static::existsByQueryBuilder(
            qb: $qb,
            useQueryCache: $useQueryCache,
            cacheId: $cacheId,
            resultCacheTags: $resultCacheTags,
        );
    }
}
