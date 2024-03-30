#!/bin/bash

packages=(
  "Component/SearchEngine"
  "Component/SearchEngineEs"
  "Component/SearchEngineMs"
  "Component/Persistence"
  "Component/PersistenceDoctrine"
  "Component/PersistenceEloquent"
  "Component/DoctrineToEs"
  "Component/FilterToEsDsl"
  "Bundle/SymfonyBridgeBundle"
)

for package in "${packages[@]}";
do
  echo $package
  vendor/bin/phpunit --configuration=src/$package
done
