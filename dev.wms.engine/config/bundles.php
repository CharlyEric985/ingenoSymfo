<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => ['all' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class                    => ['all' => true],
    Symfony\Bundle\DebugBundle\DebugBundle::class                        => ['dev' => true, 'test' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class                        => ['dev' => true],
    App\Wmsuccess\BackOffice\AdminBundle\AdminBundle::class              => ['all' => true],
    App\Wmsuccess\FrontOffice\FrontBundle\FrontBundle::class             => ['all' => true],
    App\Wmsuccess\Service\MetierManagerBundle\MetierManagerBundle::class => ['all' => true],
    App\Wmsuccess\Service\UserBundle\UserBundle::class              => ['all' => true],
    App\Wmsuccess\Service\ApiBundle\ApiBundle::class                     => ['all' => true],
    Liip\ImagineBundle\LiipImagineBundle::class                          => ['all' => true],
    Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle::class            => ['all' => true],
];
