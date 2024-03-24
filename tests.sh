#!/bin/bash

packages=(
  "Component//SearchEngineRaw"
  "Component//SearchEngineElastica"
  "Component/Persistence"
  "Component/PersistenceDoctrine"
  "Bundle/SymfonyBridgeBundle"
)

for package in "${packages[@]}";
do
  echo $package
  vendor/bin/phpunit --configuration=src/$package --bootstrap=tests/bootstrap.php
done




