<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle\ClassLoader;

/**
 * This class autoload files
 */
class GeneratedFilesLoader
{
    protected $base_path;

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
     * @param string $base_path
     * @return string
     */
    public function setBasePath($base_path)
    {
        return $this->base_path = $base_path;
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
            $file_path = $this->base_path . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

            if (file_exists($file_path)) {
                require $file_path;
            }
        }
    }
}