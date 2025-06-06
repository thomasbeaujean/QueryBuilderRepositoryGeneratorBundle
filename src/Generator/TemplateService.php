<?php

namespace Tbn\QueryBuilderRepositoryGeneratorBundle\Generator;

use Doctrine\ORM\Mapping\AssociationMapping;
use Doctrine\ORM\Mapping\ClassMetadata;
use Twig\Environment;

class TemplateService
{
    public function __construct(
        private bool $useInterfaces,
        private $topRepositoryTemple,
        private $columnTemplate,
        private $bottomRepositoryTemplate,
        private $associationTemplate,
        private Environment $twig,
    ) {
    }

    public function renderTopClass(
        string $extendClass,
        bool $isExtendedClass,
        string $namespace,
        string $entityClasspath,
        string $entityClassname,
        ?string $bundleName,
        string $entityDql,
        $idType,
        $rootEntityName,
    ): string {
        $topClassparameter = array(
            'namespace' => $namespace,
            'useInterfaces' => $this->useInterfaces,
            'entityClasspath' => $entityClasspath,
            'entityClassname' => $entityClassname,
            'extendClass' => $extendClass,
            'isExtendedClass' => $isExtendedClass,
            'bundleName' => $bundleName,
            'entityDql' => $entityDql,
            'idType' => $idType,
            'rootEntityName' => $rootEntityName,
        );

        return $this->twig->render($this->topRepositoryTemple, $topClassparameter);
    }

    public function renderAssociation(
        AssociationMapping $associationMapping,
        string $entityDql,
        ClassMetadata $targetEntityMetadata,
        string $entityDqlTargeted,
        string $entityClasspath,
    ): string {
        $idType = null;

        if (isset($targetEntityMetadata->fieldMappings['id'])) {
            $idField = $targetEntityMetadata->fieldMappings['id'];
            $idType = $idField['type'];
        }

        $fieldName = $associationMapping['fieldName'];
        $parameters = array(
            'entityDql' => $entityDql,
            'column' => ucfirst($fieldName),
            'columnDql' => $fieldName,
            'idType' => $idType,
            'targetEntity' => $associationMapping['targetEntity'],
            'entityDqlTargeted' => $entityDqlTargeted,
            'entityClasspath' => $entityClasspath,
        );

        return $this->twig->render($this->associationTemplate, $parameters);
    }

    public function renderField(
        $fieldMapping,
        $entityDql,
        string $entityClasspath,
    ): string {
        $fieldName = $fieldMapping['fieldName'];
        $parameters = array(
            'entityDql' => $entityDql,
            'column' => ucfirst($fieldName),
            'columnDql' => $fieldName,
            'entityClasspath' => $entityClasspath,
        );

        return $this->twig->render($this->columnTemplate, $parameters);
    }

    public function renderBottomClass(): string
    {
        return $this->twig->render($this->bottomRepositoryTemplate);
    }
}
