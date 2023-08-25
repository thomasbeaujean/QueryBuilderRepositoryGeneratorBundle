<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle\Configuration;

class Configurator
{
    const DEFAULT_REPOSITORY_EXTEND = '\\Doctrine\\Bundle\\DoctrineBundle\\Repository\\ServiceEntityRepository';

    public function __construct(
        private array $entityConfigurations = [],
        private array $repositoryExtensions = [],
    ) {
    }

    public function getEntityDqlName(string $entityName): string
    {
        if (isset($this->entityConfigurations[$entityName])) {
            $entityDqlName = $this->entityConfigurations[$entityName]['querybuilder_name'];
        } else {
            $pathParts = explode('\\', $entityName);
            $entityClassname = end($pathParts);

            $entityDqlName = lcfirst($entityClassname);
        }

        return $entityDqlName;
    }

    public function getExtendRepository(string $entityName): string
    {
        if (isset($this->repositoryExtensions[$entityName])) {
            $extendRepository = $this->repositoryExtensions[$entityName]['extension_class'];
        } else {
            $extendRepository = static::DEFAULT_REPOSITORY_EXTEND;
        }

        return $extendRepository;
    }
}
