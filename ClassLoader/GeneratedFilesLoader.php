<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle\ClassLoader;

/**
 * This class autoload files
 */
class GeneratedFilesLoader
{
    protected $basePath;

    /**
     * Registers this instance as an autoloader.
     *
     * @param Boolean $prepend Whether to prepend the autoloader or not
     */
    public function register($prepend = false)
    {
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);
    }

    /**
     *
     * @param string $basePath
     * @return string
     */
    public function setBasePath($basePath)
    {
        return $this->basePath = $basePath;
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $class The name of the class
     *
     */
    public function loadClass($class)
    {
        if (0 === strpos($class, 'tbn')) {
            $filePath = $this->basePath.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';

            if (file_exists($filePath)) {
                require $filePath;
            }
        }
    }
}
