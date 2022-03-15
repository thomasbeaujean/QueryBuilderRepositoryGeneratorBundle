<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle\Generator;

use Doctrine\Bundle\DoctrineBundle\Mapping\DisconnectedMetadataFactory;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Component\Filesystem\Filesystem;
use tbn\QueryBuilderRepositoryGeneratorBundle\Configuration\Configurator;
use Twig\Environment;

class RepositoryGenerator
{
    protected $configurator = null;
    private $doctrineHelper;

    /**
     * @var Environment
     */
    private  $twig;

    public function __construct(
        $topRepositoryTemple,
        $columnTemplate,
        $bottomRepositoryTemplate,
        $bundles,
        DoctrineHelper $doctrineHelper,
        $kernel,
        Configurator $configurator,
        $associationTemplate,
        $twig
    ) {
        $this->doctrineHelper = $doctrineHelper;
        $this->kernel = $kernel;
        //the bundles to scan
        $this->bundles = $bundles;

        //the templates
        $this->topRepositoryTemple = $topRepositoryTemple;
        $this->columnTemplate = $columnTemplate;
        $this->bottomRepositoryTemplate = $bottomRepositoryTemplate;
        $this->associationTemplate = $associationTemplate;

        $this->configurator = $configurator;
        $this->twig = $twig;
    }

    /**
     * Generate all files
     *
     */
    public function generateFiles()
    {
        //the bundles to scan
        $bundles = $this->bundles;
        $configurator = $this->configurator;

        //services
        $twig = $this->twig;

        //parse the bundles
        foreach ($bundles as $bundleName) {
            //the path of the files
            $allMetadata = $this->doctrineHelper->getMetadata($bundleName);

            /** @var ClassMetadata $meta */
            foreach ($allMetadata as $meta) {
                $customRepositoryClassName = $meta->customRepositoryClassName;
                if ($customRepositoryClassName) {
                    $entityClasspath = $meta->name;
                    $fieldMappings = $meta->fieldMappings;
                    $associationMappings = $meta->associationMappings;
                    $customRepositoryClassName = $meta->customRepositoryClassName;

                    $pathParts = explode('\\', $customRepositoryClassName);
                    $entityClassname = end($pathParts);

                    $entityDql = $configurator->getEntityDqlName($meta->name);

                    $entityNamespace = $this->getNamespaceFromFilepath($customRepositoryClassName);

                    $idType = null;
                    if (isset($meta->fieldMappings['id'])) {
                        $idField = $meta->fieldMappings['id'];
                        $idType = $idField['type'];
                    }
                    $renderedTemplate = $this->renderTopClass($entityNamespace, $entityClasspath, $entityClassname, $bundleName, $entityDql, $idType);

                    //parse the columns
                    foreach ($fieldMappings as $fieldMapping) {
                        $renderedTemplate .= $this->renderField($fieldMapping, $entityDql);
                    }

                    foreach ($associationMappings as $associationMapping) {
                        $renderedTemplate .= $this->renderAssociation($associationMapping, $entityDql);
                    }

                    //get the bottom template
                    $renderedTemplate .= $twig->render($this->bottomRepositoryTemplate);

                    //store the generated content
                    $reflector = new \ReflectionClass($customRepositoryClassName);
                    $originalRepostoryPath = $reflector->getFileName();
                    $fullPath = str_replace('.php', 'Base.php', $originalRepostoryPath);

                    if (!empty($customRepositoryClassName)) {
                        $this->persistClass($fullPath, $renderedTemplate);
                    }
                }
            }
        }
    }

    protected function createRepertory($path)
    {
        $fs = new Filesystem();
        $fs->mkdir($path);
    }

    protected function persistClass($filePath, $content)
    {
        $directory = $this->getDirectoryFromFilepath($filePath);

        //create if needed the repertory
        $this->createRepertory($directory);
        $this->putFileContent($filePath, $content);
    }

    protected function getDirectoryFromFilepath($filePath): string
    {
        $pathParts = explode('/', $filePath);
        array_pop($pathParts);

        return implode('/', $pathParts);
    }

    protected function getNamespaceFromFilepath($filePath): string
    {
        $pathParts = explode('\\', $filePath);
        array_pop($pathParts);

        return implode('\\', $pathParts);
    }

    protected function renderTopClass($namespace, $entityClasspath, $entityClassname, $bundleName, $entityDql, $idType): string
    {
        //services
        $twig = $this->twig;

        $extendClass = $this->configurator->getExtendRepository($entityClasspath);

        $topClassparameter = array(
            'namespace' => $namespace,
            'entityClasspath' => $entityClasspath,
            'entityClassname' => $entityClassname,
            'extendClass' => $extendClass,
            'bundleName' => $bundleName,
            'entityDql' => $entityDql,
            'idType' => $idType,
        );

        return $twig->render($this->topRepositoryTemple, $topClassparameter);
    }

    protected function renderAssociation($associationMapping, $entityDql): string
    {
        //services
        $twig = $this->twig;

        $fieldName = $associationMapping['fieldName'];
        $parameters = array(
            'entityDql' => $entityDql,
            'column' => ucfirst($fieldName),
            'columnDql' => $fieldName,
        );

        return $twig->render($this->associationTemplate, $parameters);
    }

    protected function renderField($fieldMapping, $entityDql): string
    {
        //services
        $twig = $this->twig;

        $fieldName = $fieldMapping['fieldName'];
        $parameters = array(
            'entityDql' => $entityDql,
            'column' => ucfirst($fieldName),
            'columnDql' => $fieldName,
        );

        return $twig->render($this->columnTemplate, $parameters);
    }

    protected function putFileContent($fileName, $content)
    {
        // Stores the cache
        file_put_contents($fileName, $content);
    }
}
