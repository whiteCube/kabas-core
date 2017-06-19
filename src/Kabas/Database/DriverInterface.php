<?php

namespace Kabas\Database;

use Kabas\Config\Language;
use Kabas\Model\Model;

interface DriverInterface
{
    public function setLocale(Language $locale);

    public function makeNewQuery(string $method, array $arguments);

    public function makeModelQuery(Model $item, string $method, array $arguments);
}
