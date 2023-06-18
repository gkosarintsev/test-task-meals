<?php

namespace tests\Meals;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function(ContainerConfigurator $configurator) {

    $services = $configurator->services()
        ->defaults()
        ->public()
        ->autowire()
        ->autoconfigure()
    ;

    $services->load('Meals\\Application\\Feature\\', '../src/Application/Feature/*');
    $services->load('Meals\\Application\\Component\\Provider\\', '../src/Application/Component/Provider/*');
    $services->load('Meals\\Application\\Component\\Validator\\', '../src/Application/Component/Validator/*');
    $services->load('tests\\Meals\\Functional\\Fake\\', '../tests/Functional/Fake/*');
};
