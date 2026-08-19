<?php

declare(strict_types=1);

namespace ArtisanSdk\Model\Tests\Fakes\App;

/**
 * Fakes the App facade root with a Laravel-12-or-later version.
 */
class PostTwelve
{
    public int $calls = 0;

    public function version(): string
    {
        $this->calls++;

        return '12.0.0';
    }
}
