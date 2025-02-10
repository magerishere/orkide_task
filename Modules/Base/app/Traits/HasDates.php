<?php

namespace Modules\Base\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\Base\Enums\DateTimeFormat;

trait HasDates
{
    public function createdAtText(): Attribute
    {
        return Attribute::get(
            fn() => $this->created_at->format(DateTimeFormat::DATE_TIME_WITH_DASH_AND_TIME_WITH_DOUBLE_POINT->value)
        );
    }

    public function updatedAtText(): Attribute
    {
        return Attribute::get(
            fn() => $this->updated_at->format(DateTimeFormat::DATE_TIME_WITH_DASH_AND_TIME_WITH_DOUBLE_POINT->value)
        );
    }
}
