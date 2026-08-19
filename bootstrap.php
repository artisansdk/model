<?php

declare(strict_types=1);

use Composer\InstalledVersions;

if (! defined('LARAVEL_VERSION')) {
    define('LARAVEL_VERSION', InstalledVersions::getPrettyVersion('illuminate/database') ?? '0.0.0');
}
