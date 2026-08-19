<?php

declare(strict_types=1);

namespace ArtisanSdk\Model\Tests\Unit;

use ArtisanSdk\Model\Tests\Fakes\App\{PostTwelve, PreTwelve};
use ArtisanSdk\Model\Tests\Fakes\Models\BootableModel;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

beforeEach(function () {
    // Force bootValidation() to run again each test instead of skipping
    // because a prior test already booted this class.
    Model::clearBootedModels();
    BootableModel::reset();
});

test('bootValidation observes immediately before Laravel 12', function () {
    App::swap(new PreTwelve);

    new BootableModel;

    expect(BootableModel::$observeCalls)->toBe(1)
        ->and(BootableModel::$deferred)->toBeNull();
});

test('bootValidation defers observing via whenBooted on Laravel 12+', function () {
    App::swap(new PostTwelve);

    new BootableModel;

    expect(BootableModel::$observeCalls)->toBe(0)
        ->and(BootableModel::$deferred)->toBeInstanceOf(Closure::class);

    (BootableModel::$deferred)();

    expect(BootableModel::$observeCalls)->toBe(1);
});
