<?php

declare(strict_types=1);

namespace ArtisanSdk\Model\Tests\Fakes\App;

/**
 * Fakes the App facade root with a pre-Laravel-12 version.
 */
class PreTwelve
{
    public int $calls = 0;

    public function version(): string
    {
        $this->calls++;

        return '11.9.0';
    }
}
