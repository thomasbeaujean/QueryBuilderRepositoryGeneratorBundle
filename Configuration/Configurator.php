<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle\Configuration;

/**
 *
 * @author Thomas BEAUJEAN
 *
 */
class Configurator
{
    protected $entityConfigurations = [];

    /**
     *
     * @param type $entityConfigurations
     */
    public function __construct($entityConfigurations)
    {
        $this->entityConfigurations = $entityConfigurations;
    }

    /**
     *
     * @param string $entityName
     * @param string $tableName
     *
     * @return string The
     */
    public function getEntityDqlName($entityName, $tableName)
    {
        if (isset($this->entityConfigurations[$entityName])) {
            $entityDqlName = $this->entityConfigurations[$entityName]['querybuilder_name'];
        } else {
            $entityDqlName = lcfirst($tableName);
        }

        return $entityDqlName;
    }
}
