<?php

declare(strict_types=1);

namespace ArtisanSdk\Model\Tests\Fakes\Models;

use ArtisanSdk\Model\Eloquent;
use ArtisanSdk\Model\Observers\Validation as Observer;
use Closure;
use Illuminate\Support\Facades\App;

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

    public static bool $didBoot = false;

    public static bool $observedAfterBoot = false;

    protected static array $callbacks = [];

    public function rules(): array
    {
        return [];
    }

    public static function observe($classes)
    {
        if ($classes instanceof Observer) {
            static::$observeCalls++;
            static::$observedAfterBoot = static::$didBoot;
        }
    }

    protected static function booted()
    {
        static::$didBoot = true;

        if (version_compare(App::version(), '12.0.0', '<')) {
            foreach (static::$callbacks[static::class] ?? [] as $callback) {
                $callback();
            }
        }
    }

    protected static function whenBooted(Closure $callback)
    {
        if (version_compare(App::version(), '12.0.0', '>=')) {
            parent::whenBooted($callback);

            return;
        }

        static::$callbacks[static::class][] = $callback;
    }

    public static function reset()
    {
        static::$observeCalls = 0;
        static::$didBoot = false;
        static::$observedAfterBoot = false;
        static::$callbacks = [];
    }
}
