<?php

namespace FHPlatform\Bundle\ConfigBundle\Util\Namer;

class IndexNamer
{
    public function getName(string $className): string
    {
        $classNameParts = explode('\\', $className);
        $classNameLast = (string) end($classNameParts);

        $classNameLast = str_replace('_', '', ucwords($classNameLast, '_'));

        return $this->camelToSnake($classNameLast);
    }

    private function camelToSnake($input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}
