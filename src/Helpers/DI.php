<?php

namespace Helpers;

use Symfony\Component\DependencyInjection\ContainerBuilder;

$container = new ContainerBuilder();

$container->
register(
    'brandRepository', 'Repositories\BrandRepository')
    ->addArgument(DBConnection::getInstance()->getConnection());

$container->
register(
    'modelRepository', 'Repositories\ModelRepository')
    ->addArgument(DBConnection::getInstance()->getConnection());

$container->
register(
    'generationRepository', 'Repositories\GenerationRepository')
    ->addArgument(DBConnection::getInstance()->getConnection());

$container->
register(
    'carRepository', 'Repositories\CarRepository')
    ->addArgument(DBConnection::getInstance()->getConnection());

$container->
register(
    'logbookRepository', 'Repositories\LogbookRepository')
    ->addArgument(DBConnection::getInstance()->getConnection());

$container->
register(
    'carsImagesRepository', 'Repositories\CarsImagesRepository')
    ->addArgument(DBConnection::getInstance()->getConnection());
