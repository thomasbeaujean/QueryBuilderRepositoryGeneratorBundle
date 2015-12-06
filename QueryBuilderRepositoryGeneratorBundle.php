<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 *
 * @author Thomas BEAUJEAN
 *
 */
class QueryBuilderRepositoryGeneratorBundle extends Bundle
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\HttpKernel\Bundle\Bundle::boot()
     */
    public function boot()
    {
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\HttpKernel\Bundle\Bundle::build()
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}
