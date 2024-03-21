#!/bin/bash

bundles=(
  "Bundle/ClientElasticaBundle"
  "Bundle/UtilBundle"
  "Bundle/PersistenceBundle"
  "Component/ClientBundle"
  "Bundle/DataSyncBundle"
)

for bundle in "${bundles[@]}";
do
  echo $bundle
  vendor/bin/phpunit --configuration=src/$bundle --bootstrap=vendor/autoload.php
done




