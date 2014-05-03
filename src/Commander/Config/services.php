<?php

use Commander\Core\Environment;
use DI\Container;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\FileCacheReader;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

return [
    'template.path'         => "",
    'twig.options'          => ['cache' => 'cache/twig'],
    'System'                => DI\object('Commander\Core\System'),
    'AnnotationReader'      => DI\factory(
        function (Container $c) {
            $reader = new FileCacheReader(
                new AnnotationReader(),
                $c->get("Config")->get("path.cache", ROOT . "cache"),
                $debug = true
            );

            return $reader;
        }
    ),
    'DoctrineCache'         => DI\factory(
        function (Container $c) {
            $config = $c->get("Config");
            $cache  = new \Doctrine\Common\Cache\FilesystemCache($config->get("path.cache", ROOT . "cache"));

            return $cache;
        }
    ),
    'AccessManager'         => DI\object('\Commander\Core\AccessManager'),
    'Logger'                => DI\factory(
        function () {
            $logger = new Logger('commander');

            $fileHandler = new StreamHandler(
                ROOT . 'log/' . strtolower(Environment::current()) . ".log",
                Logger::DEBUG
            );
            $fileHandler->setFormatter(new LineFormatter());
            $logger->pushHandler($fileHandler);

            return $logger;
        }
    ),
    'Router'                => DI\object('\AltoRouter'),
    'TwigLoader'            => DI\object('Twig_Loader_Filesystem')->constructorParameter(
                               "paths",
                               DI\link("template.path")
        ),
    'Twig'                  => DI\object('\Twig_Environment')
                               ->constructorParameter("loader", DI\link("TwigLoader"))
                               ->constructorParameter("options", DI\link("twig.options")),
    'Response'              => DI\object('\Commander\Core\Response\HttpResponse'),
    'RouteNotFoundResponse' => DI\object('\Commander\Error\Error404')
];
