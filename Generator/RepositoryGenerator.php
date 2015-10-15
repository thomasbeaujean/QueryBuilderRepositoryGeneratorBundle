<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle\Generator;

use Doctrine\Bundle\DoctrineBundle\Mapping\DisconnectedMetadataFactory;
use Symfony\Component\Filesystem\Filesystem;
use tbn\QueryBuilderRepositoryGeneratorBundle\Configuration\Configurator;

/**
 *
 * @author Thomas BEAUJEAN
 *
 */
class RepositoryGenerator
{
    protected $configurator = null;

    /**
     *
     * @param type         $topRepositoryTemple
     * @param type         $columnTemplate
     * @param type         $bottomRepositoryTemplate
     * @param type         $bundles
     * @param type         $doctrine
     * @param type         $em
     * @param type         $kernel
     * @param type         $twig
     * @param Configurator $configurator
     * @param string       $associationTemplate
     */
    public function __construct($topRepositoryTemple, $columnTemplate, $bottomRepositoryTemplate, $bundles, $doctrine, $em, $kernel, $twig, Configurator $configurator, $associationTemplate)
    {
        $this->doctrine = $doctrine;
        $this->em = $em;
        $this->kernel = $kernel;
        $this->twig = $twig;

        //the bundles to scan
        $this->bundles = $bundles;

        //the templates
        $this->topRepositoryTemple = $topRepositoryTemple;
        $this->columnTemplate = $columnTemplate;
        $this->bottomRepositoryTemplate = $bottomRepositoryTemplate;
        $this->associationTemplate = $associationTemplate;

        $this->configurator = $configurator;
    }

    /**
     * Create the path
     *
     * @param string $path
     */
    protected function createRepertory($path)
    {
        $fs = new Filesystem();
        $fs->mkdir($path);
    }

    /**
     * Generate all files
     *
     * @param string $directory
     */
    public function generateFiles($directory)
    {
        //the bundles to scan
        $bundles = $this->bundles;
        $configurator = $this->configurator;

        //services
        $twig = $this->twig;

        //parse the bundles
        foreach ($bundles as $bundleName) {
            //the path of the files
            $allMetadata = $this->getAllMetadata(array($bundleName));
            $metadata = $allMetadata->getMetadata();

            foreach ($metadata as $meta) {
                $entityClasspath = $meta->name;
                $fieldMappings = $meta->fieldMappings;
                $associationMappings = $meta->associationMappings;

                $pathParts = explode('\\', $entityClasspath);
                $entityClassname = end($pathParts);

                $entityDql = $configurator->getEntityDqlName($entityClasspath);

                $renderedTemplate = $this->renderTopClass($entityClasspath, $entityClassname, $bundleName, $entityDql);

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
                $fullPath = $directory.'/'.$entityClasspath.'Repository.php';
                $fullPath = str_replace('\\', '/', $fullPath);

                $this->persistClass($fullPath, $renderedTemplate);
            }
        }
    }

    /**
     *
     * @param string $filePath
     * @param string $content
     */
    protected function persistClass($filePath, $content)
    {
        $pathParts = explode('/', $filePath);
        array_pop($pathParts);
        $directory = implode('/', $pathParts);

        //create if needed the repertory
        $this->createRepertory($directory);
        $this->putFileContent($filePath, $content);
    }
    /**
     *
     * @param string $entityClasspath
     * @param string $entityClassname
     * @param string $bundleName
     * @param string $entityDql
     * @return type
     */
    protected function renderTopClass($entityClasspath, $entityClassname, $bundleName, $entityDql)
    {
        //services
        $twig = $this->twig;

        $extendClass = $this->configurator->getExtendRepository($entityClasspath);

        $entityClasspath = str_replace('\\', '/', $entityClasspath);
        $pathParts = explode('/', $entityClasspath);
        array_pop($pathParts);
        $namespace = implode('\\', $pathParts);

        $topClassparameter = array(
            'namespace' => $namespace,
            'entityClassname' => $entityClassname,
            'extendClass' => $extendClass,
            'bundleName' => $bundleName,
            'entityDql' => $entityDql,
        );

        $renderedTemplate = $twig->render($this->topRepositoryTemple, $topClassparameter);

        return $renderedTemplate;
    }

    /**
     *
     * @return string
     */
    protected function renderAssociation($associationMapping, $entityDql)
    {
        //services
        $twig = $this->twig;

        $fieldName = $associationMapping['fieldName'];
        $parameters = array(
            'entityDql' => $entityDql,
            'column' => ucfirst($fieldName),
            'columnDql' => $fieldName,
        );

        $renderedTemplate = $twig->render($this->associationTemplate, $parameters);

        return $renderedTemplate;
    }

    /**
     *
     * @return string
     */
    protected function renderField($fieldMapping, $entityDql)
    {
        //services
        $twig = $this->twig;

        $fieldName = $fieldMapping['fieldName'];
        $parameters = array(
            'entityDql' => $entityDql,
            'column' => ucfirst($fieldName),
            'columnDql' => $fieldName,
        );

        $renderedTemplate = $twig->render($this->columnTemplate, $parameters);

        return $renderedTemplate;
    }

    /**
     * Store the content in the cache file
     *
     * @param string $fileName
     * @param string $content
     */
    protected function putFileContent($fileName, $content)
    {
        // Stores the cache
        file_put_contents($fileName, $content);
    }

    /**
     *
     * @param array $bundles
     * @return Ambigous <multitype:, \tbn\QueryBuilderRepositoryGeneratorBundle\Generator\metadata>
     */
    protected function getAllMetadata($bundles)
    {
        $metadata = array();
        foreach ($bundles as $name) {
            $metadata = $this->getAllMetadataFromBundle($name);
        }

        return $metadata;
    }

    /**
     *
     * @param string $bundleName
     * @return metadata
     */
    protected function getAllMetadataFromBundle($bundleName)
    {
        $manager = new DisconnectedMetadataFactory($this->doctrine);

        $bundle = $this->kernel->getBundle($bundleName);

        $metadata = $manager->getBundleMetadata($bundle);

        return $metadata;
    }
}
