<?php

return [
    'AnnotationReader' => DI\object('\Doctrine\Common\Annotations\AnnotationReader'),
    'DoctrineCache' => DI\object('\Doctrine\Common\Cache\ArrayCache'),
    'AnnotationEvent::Role' => DI\object('\LootManager\Annotation\Role')
] + require "services.php";
