<?php

namespace Tbn\QueryBuilderRepositoryGeneratorBundle\Tests\Service;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\HttpKernel\KernelInterface;
use Tbn\QueryBuilderRepositoryGeneratorBundle\Generator\RepositoryGenerator;
use Tbn\QueryBuilderRepositoryGeneratorBundle\QueryBuilderRepositoryGeneratorBundle;

class RepositoryGeneratorTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    protected static function createKernel(array $options = []): KernelInterface
    {
        /**
         * @var TestKernel $kernel
         */
        $kernel = parent::createKernel($options);
        $kernel->addTestBundle(DoctrineBundle::class);
        $kernel->addTestBundle(MakerBundle::class);
        $kernel->addTestBundle(TwigBundle::class);
        $kernel->addTestBundle(QueryBuilderRepositoryGeneratorBundle::class);
        $kernel->handleOptions($options);
        $kernel->addTestConfig(__DIR__.'/../config.yml');

        return $kernel;
    }

    public function testGenerateFiles(): void
    {
        self::bootKernel();

        $container = self::getContainer();

        $this->assertTrue($container->has('tbn_qbrg.generator.repository_generator'));

        /** @var RepositoryGenerator */
        $service = $container->get('tbn_qbrg.generator.repository_generator');
        $service->generateFiles();

        $content = \file_get_contents(__DIR__.'/../Repository/MyClassRepositoryBase.php');

        // keep for dev update
        // \file_put_contents(__DIR__.'/ExpectedMyClassRepositoryBase.txt', $content);

        $expectedContent = \file_get_contents(__DIR__.'/ExpectedMyClassRepositoryBase.txt');

        $this->assertSame($expectedContent, $content);
    }
}
