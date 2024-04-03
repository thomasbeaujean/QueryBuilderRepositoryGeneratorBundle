<?php

namespace Tbn\QueryBuilderRepositoryGeneratorBundle\Generator;

use Doctrine\ORM\Mapping\ClassMetadata;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Tbn\QueryBuilderRepositoryGeneratorBundle\Configuration\Configurator;

class RepositoryGenerator
{
    public function __construct(
        private TemplateService $templateService,
        private $bundles,
        private DoctrineHelper $doctrineHelper,
        private Configurator $configurator,
        private Persister $persister,
        private LoggerInterface $logger,
    ) {
    }

    public function generateFiles()
    {
        //the bundles to scan
        $bundles = $this->bundles;

        //parse the bundles
        foreach ($bundles as $bundleName) {
            $this->logger->info('Bundle analysed: '. $bundleName);

            //the path of the files
            $allMetadata = $this->doctrineHelper->getMetadata($bundleName);

            /** @var ClassMetadata $meta */
            foreach ($allMetadata as $meta) {
                $customRepositoryClassName = $meta->customRepositoryClassName;
                if ($meta->customRepositoryClassName === null) {
                    $this->logger->info('SKIPPING entity: '. $meta->name);
                    continue;
                }

                $this->logger->info('Entity: '. $meta->name);
                $renderedTemplate = $this->generateRepository($allMetadata, $bundleName, $meta);

                //store the generated content
                $reflector = new \ReflectionClass($customRepositoryClassName);
                $originalRepostoryPath = $reflector->getFileName();
                $fullPath = str_replace('.php', 'Base.php', $originalRepostoryPath);

                $this->persister->persistClass($fullPath, $renderedTemplate);
            }
        }
    }

    private function generateRepository(array $allMetadata, string $bundleName, ClassMetadata $meta)
    {
        $entityClasspath = $meta->name;
        $fieldMappings = $meta->fieldMappings;
        $associationMappings = $meta->associationMappings;
        $customRepositoryClassName = $meta->customRepositoryClassName;

        $pathParts = explode('\\', $customRepositoryClassName);
        $entityClassname = end($pathParts);

        $entityDql = $this->configurator->getEntityDqlName($meta->name);

        $entityNamespace = $this->getNamespaceFromFilepath($customRepositoryClassName);

        $idType = null;
        if (isset($meta->fieldMappings['id'])) {
            $idField = $meta->fieldMappings['id'];
            $idType = $idField['type'];
        }
        $extendClass = $this->configurator->getExtendRepository($entityClasspath);
        $renderedTemplate = $this->templateService->renderTopClass(
            $extendClass,
            $entityNamespace,
            $entityClasspath,
            $entityClassname,
            $bundleName,
            $entityDql,
            $idType,
        );

        //parse the columns
        foreach ($fieldMappings as $fieldMapping) {
            $renderedTemplate .= $this->templateService->renderField(
                $fieldMapping,
                $entityDql,
                $entityClasspath,
            );
        }

        foreach ($associationMappings as $associationMapping) {
            $targetEntityMetadata = $allMetadata[$associationMapping['targetEntity']];

            $renderedTemplate .= $this->templateService->renderAssociation(
                $associationMapping,
                $entityDql,
                $targetEntityMetadata,
                $this->configurator->getEntityDqlName($associationMapping['targetEntity']),
                $entityClasspath,
            );
        }

        //get the bottom template
        $renderedTemplate .= $this->templateService->renderBottomClass();

        return $renderedTemplate;
    }

    private function getNamespaceFromFilepath($filePath): string
    {
        $pathParts = explode('\\', $filePath);
        array_pop($pathParts);

        return implode('\\', $pathParts);
    }
}
