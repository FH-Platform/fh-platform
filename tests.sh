#!/bin/bash

bundles=(
  "Component/ClientElastica"
  "Bundle/UtilBundle"
  "Bundle/PersistenceBundle"
  "Bundle/PersistenceDoctrineBundle"
)

for bundle in "${bundles[@]}";
do
  echo $bundle
  vendor/bin/phpunit --configuration=src/$bundle --bootstrap=vendor/autoload.php
done




