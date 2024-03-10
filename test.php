<?php

include 'vendor/autoload.php';

$bundles = [
    'UtilBundle',
    'ConfigBundle',
    'PersistenceBundle',
];

exec('vendor/bin/phpunit --configuration=src/Bundle/PersistenceBundle --bootstrap=vendor/autoload.php');

foreach ($bundles as $bundle){
    echo  $bundle;
}

echo (\FHPlatform\ConfigBundle\Finder\ProviderFinder::class);

echo 'test';


