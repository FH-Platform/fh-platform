#!/bin/bash

bundles=(
  "ConfigBundle"
  "UtilBundle"
  "PersistenceBundle"
  "ClientBundle"
  "DataSyncBundle"
)

for bundle in "${bundles[@]}";
do
  echo $bundle
  vendor/bin/phpunit --configuration=src/Bundle/$bundle --bootstrap=vendor/autoload.php
done




