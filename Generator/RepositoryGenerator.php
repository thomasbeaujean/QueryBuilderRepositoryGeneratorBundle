<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle\Generator;


use Doctrine\ORM\EntityManager;

use Doctrine\Bundle\DoctrineBundle\Mapping\DisconnectedMetadataFactory;
use Symfony\Component\HttpKernel\Kernel;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Filesystem\Filesystem;

/**
 *
 * @author Thomas BEAUJEAN
 *
 * @todo Clean this files, make it more readable
 *
 */
class RepositoryGenerator
{
    /**
     *
     * @param unknown $topRepositoryTemple
     * @param unknown $columnTemplate
     * @param unknown $bottomRepositoryTemplate
     * @param unknown $doctrine
     * @param unknown $em
     * @param unknown $kernel
     * @param unknown $twig
     */
    public function __construct($topRepositoryTemple, $columnTemplate, $extraColumnTemplate, $bottomRepositoryTemplate, $bundles, $doctrine, $em, $kernel, $twig)
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
        $this->extraColumnTemplate = $extraColumnTemplate;
        $this->bottomRepositoryTemplate = $bottomRepositoryTemplate;
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

        //services
        $twig = $this->twig;

        //parse the bundles
        foreach ($bundles as $bundleName) {
            //the path of the files
            $path = $directory.'/'.$bundleName.'/Repository';

            $description = $this->getDatabaseDescription(array($bundleName));

            $fieldNames = $description['fieldNames'];
            $tableNames = $description['tableNames'];

            foreach ($tableNames as $tableIndex => $tableName) {
                $renderedTemplate = $twig->render($this->topRepositoryTemple, array('tableName' => $tableName, 'bundleName' => $bundleName));

                //parse the columns
                foreach ($fieldNames[$tableIndex] as $columnName) {
                    $parameters = array(
                        'entity' => $tableName,
                        'entityDql' => lcfirst($tableName),
                        'column' => ucfirst($columnName),
                        'columnDql' => $columnName
                    );

                    $renderedTemplate .= $twig->render($this->columnTemplate, $parameters);

                    //if an extra template has been provided
                    if ($this->extraColumnTemplate !== '') {
                        //we render this extra template for the columns
                        $renderedTemplate .= $twig->render($this->extraColumnTemplate, $parameters);
                    }
                }

                //get the bottom template
                $renderedTemplate .= $twig->render($this->bottomRepositoryTemplate);

                //create if needed the repertory
                $this->createRepertory($path);

                //store the generated content
                $fileName = $path.'/'.$tableName.'Repository.php';
                $this->putFileContent($fileName, $renderedTemplate);
            }
        }
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
     * getDatabaseDescription
     * @param EntityManager $customEm
     */
    public function getDatabaseDescription($bundles, EntityManager $customEm = null)
    {
        $em = $customEm;
        if (null === $customEm) {//if the custom em is given, we use it
            $em = $this->em;
        }

        $metadata = $this->getAllMetadata($bundles);

        $check = array();
        $check['tables'] = array();
        $check['fields'] = array();
        $check['fieldNames'] = array();
        $check['tableNames'] = array();

        $ignoreClasses = array();

        //look for subclasses that does not have the correct table Name.
        foreach ($metadata->getMetadata() as $fieldMapping) {
            $ignoreClasses = array_merge($ignoreClasses, $fieldMapping->subClasses);
        }

        //ignore subclasses
        foreach ($metadata->getMetadata() as $fieldMapping) {
            if (!in_array($fieldMapping->name, $ignoreClasses)) {
                $tableName = $this->removeBrackets($fieldMapping->table['name']);
                $check['tables'][] = $tableName;

                $nameSpaceExploded = explode('\\', $fieldMapping->name);
                $tableNameSpace = $nameSpaceExploded[count($nameSpaceExploded) - 1];//get the last item
                $check['tableNames'][$tableName] = $tableNameSpace;

                $check['fieldNames'][$tableName] = array();
                foreach ($fieldMapping->fieldNames as $columnName => $fieldName) {
                    $check['fieldNames'][$tableName][$columnName] = $fieldName;
                }

                unset($tableName);
            }
        }

        foreach ($metadata->getMetadata() as $fieldMapping) {
            if (!in_array($fieldMapping->name, $ignoreClasses)) {
                $tableName = $this->removeBrackets($fieldMapping->table['name']);
                $check['fields'][$tableName] = array();

                //simple fields
                foreach ($fieldMapping->getColumnNames() as $columnName) {
                    $check['fields'][$tableName][] = $columnName;
                }

                //associated mapping
                foreach ($fieldMapping->associationMappings as $association) {
                    //if association is on the entity table
                    if (isset($association['joinColumnFieldNames'])) {
                        foreach ($association['joinColumnFieldNames'] as $columnName) {
                            $check['fields'][$tableName][] = $columnName;
                            $check['fieldNames'][$tableName][$columnName] = $association['fieldName'];
                        }
                    }
                }

                unset($tableName);
            }
        }

        return $check;
    }

    /**
     * Remove the beginning and ending bracket of the table name
     * @param string $tableName
     * @return string
     */
    protected function removeBrackets($tableName)
    {
        //remove [ ] from the tablename
        $characters = array('[', ']');
        $tableName = str_replace($characters, '', $tableName);
        unset($characters);

        return $tableName;
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
