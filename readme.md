# Self-Validating Model

A self-validating model compatible with Laravel Eloquent that validates when it's saved.

## Table of Contents

- [Self-Validating Model](#self-validating-model)
  - [Table of Contents](#table-of-contents)
  - [Installation](#installation)
  - [Usage Guide](#usage-guide)
  - [Running the Tests](#running-the-tests)
    - [Running with Docker](#running-with-docker)
  - [Licensing](#licensing)

## Installation

The package installs into a PHP application like any other PHP package:

```bash
composer require artisansdk/model
```

Now all you need to do is swap from the base `Illuminate\Database\Eloquent\Model` model
to the `ArtisanSdk\Model\Eloquent`:

```php
namespace App\Models;

use ArtisanSdk\Model\Eloquent as Model;

class User extends Model
{
    /**
     * Get the validation rules.
     */
    public function rules() : array
    {
        return [
            'name' => ['required', 'string', 'max:64'],
            'password' => ['required'],
        ];
    }
}
```

## Usage Guide

\<needs description>

## Running the Tests

The package is unit tested with 100% line coverage and path coverage. You can
run the tests by simply cloning the source, installing the dependencies, and then
running `./vendor/bin/phpunit`. Additionally included in the developer dependencies
are some Composer scripts which can assist with Code Styling and coverage reporting:

```bash
composer check
composer coverage
composer fix
composer test
composer retry
```

See the `composer.json` for more details on their execution and reporting output.

### Running with Docker

The included Dockerfile uses PHP 8.5, Composer, and Xdebug. Build the image and
run the full test suite with:

```bash
docker build -t artisansdk-model .
docker run --rm --volume "$PWD":/app artisansdk-model
```

The bind mount keeps the installed `vendor/` directory on the host, while `--rm`
removes the container after its command finishes. To run any Composer command
instead of the default install-and-test command, append it to `docker run`:

```bash
docker run --rm --volume "$PWD":/app artisansdk-model composer update --no-dev
```

Xdebug runs in coverage mode, so `composer coverage` emits both its text and
HTML coverage reports.

## Licensing

Copyright (c) 2018-2026 [Artisan Made, Co.](http://artisanmade.io)

This package is released under the MIT license. Please see the LICENSE file
distributed with every copy of the code for commercial licensing terms.
