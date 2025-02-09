<?php

namespace Modules\Base\Traits;

trait Enumable
{
    abstract public function enumsLang(): array;

    public function label()
    {
        return $this->enumsLang()[self::class][$this->name];
    }
}
