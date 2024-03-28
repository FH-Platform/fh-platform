<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests\Util;

use Monolog\Formatter\NormalizerFormatter;
use Monolog\LogRecord;

class JsonFormatter extends NormalizerFormatter
{
    public function format(LogRecord $record)
    {
        return json_encode($record, JSON_PRETTY_PRINT)."\n\n";
    }
}
