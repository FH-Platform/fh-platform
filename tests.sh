#!/bin/bash

packages=(
  "Component/ClientElastica"
  "Component/Persistence"
  "Bundle/PersistenceDoctrine"
  "Bundle/SymfonyBridgeBundle"
)

for package in "${packages[@]}";
do
  echo $package
  vendor/bin/phpunit --configuration=src/$package --bootstrap=vendor/autoload.php
done




