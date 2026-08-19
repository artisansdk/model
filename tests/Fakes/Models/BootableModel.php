<?php

declare(strict_types=1);

namespace ArtisanSdk\Model\Tests\Fakes\Models;

use ArtisanSdk\Model\Eloquent;
use ArtisanSdk\Model\Observers\Validation as Observer;
use Closure;

/**
 * Records how bootValidation() registers its observer, so tests can assert
 * whether it happened immediately or was deferred via whenBooted().
 *
 * Model's own HasEvents::bootHasEvents() also calls observe() (with an
 * empty array, resolved from #[ObservedBy] attributes) on every boot, so
 * only calls that register our Observer count as $observeCalls.
 */
class BootableModel extends Eloquent
{
    public static int $observeCalls = 0;

    public static ?Closure $deferred = null;

    public function rules(): array
    {
        return [];
    }

    public static function observe($classes)
    {
        if ($classes instanceof Observer) {
            static::$observeCalls++;
        }
    }

    public static function whenBooted($callback)
    {
        static::$deferred = $callback;
    }

    public static function reset()
    {
        static::$observeCalls = 0;
        static::$deferred = null;
    }
}
