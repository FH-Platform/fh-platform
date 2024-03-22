#!/bin/bash

packages=(
  "Component/ClientElastica"
  "Bundle/PersistenceDoctrineBundle"
  "Bundle/PersistenceBundle"
  "Bundle/SymfonyBridgeBundle"
)

for package in "${packages[@]}";
do
  echo $package
  vendor/bin/phpunit --configuration=src/$package --bootstrap=vendor/autoload.php
done




