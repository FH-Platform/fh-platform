FH-Platform is a set of reusable PHP bundles to work with ES in Symfony framework.

```bash
vendor/bin/php-cs-fixer fix
vendor/bin/phpstan analyse
vendor/bin/phpunit
php bin/console doctrine:schema:validate --skip-sync
```

```bash
php bin/console debug:router | sort -k 5
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
php bin/console doctrine:schema:validate --skip-sync
```



