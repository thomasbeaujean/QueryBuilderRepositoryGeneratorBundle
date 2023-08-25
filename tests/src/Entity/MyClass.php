<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle\Tests\src\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use tbn\QueryBuilderRepositoryGeneratorBundle\Tests\src\Repository\MyClassRepository;

#[ORM\Entity(repositoryClass:MyClassRepository::class)]
class MyClass
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::INTEGER)]
    private int $number;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    /**
     * @var Collection<ForeignClass>
     */
    #[ORM\OneToMany(targetEntity: ForeignClass::class, mappedBy: 'myClass')]
    private Collection $foreignClasses;

    /**
     * @var array<int>
     */
    private array $references;
}
