#!/bin/bash

packages=(
  "Component/SearchEngineEsGuzzle"
  "Component/SearchEngineEs"
  "Component/Persistence"
  "Component/PersistenceDoctrine"
  "Bundle/SymfonyBridgeBundle"
)

for package in "${packages[@]}";
do
  echo $package
  vendor/bin/phpunit --configuration=src/$package --bootstrap=tests/bootstrap.php
done




