<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle\Generator;

use Symfony\Component\Filesystem\Filesystem;

class Persister
{
    public function persistClass($filePath, $content)
    {
        $directory = $this->getDirectoryFromFilepath($filePath);

        //create if needed the repertory
        $this->createRepertory($directory);

        // Stores the cache
        file_put_contents($filePath, $content);
    }

    private function createRepertory(string $path): void
    {
        $fs = new Filesystem();
        $fs->mkdir($path);
    }

    private function getDirectoryFromFilepath(string $filePath): string
    {
        $pathParts = explode('/', $filePath);
        array_pop($pathParts);

        return implode('/', $pathParts);
    }
}
