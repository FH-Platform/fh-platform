#!/bin/bash

packages=(
  "Component/SearchEngine"
  "Component/SearchEngineEs"
  "Component/SearchEngineMs"
  "Component/Persistence"
  "Component/PersistenceDoctrine"
  "Component/PersistenceEloquent"
  "Component/Config"
  "Component/DoctrineToEs"
  "Component/PersistenceManager"
  "Component/FilterToEsDsl"
  "Component/Syncer"
  #"Bundle/SymfonyBridgeBundle"
)

for package in "${packages[@]}";
do
  echo $package
  vendor/bin/phpunit --configuration=src/$package/tests
done
