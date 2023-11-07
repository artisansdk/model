<?php

declare(strict_types=1);

namespace ArtisanSdk\Model\Tests\Unit;

use ArtisanSdk\Model\Eloquent;
use ArtisanSdk\Model\Exceptions\InvalidAttributes;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\MessageBag;
use Illuminate\Translation\{FileLoader, Translator};
use Illuminate\Validation\Factory;
use ReflectionClass;

class Stub extends Eloquent
{
    public function rules(): array
    {
        return [
            'foo' => ['required'],
        ];
    }
}

test('model should validate attributes against rules when saved', function () {
    $language = dirname((new ReflectionClass(Translator::class))->getFileName()).'/lang';
    $loader = new FileLoader(new Filesystem(), $language);
    $loader->addNamespace('lang', $language);
    $loader->load(locale: 'en', group: 'validation', namespace: 'lang');
    $validator = new Factory(new Translator($loader, 'en'));

    $model = new Stub();
    $model->setValidator($validator);
    $model->foo = null;

    expect(fn () => $model->isValidOrFail())
        ->toThrow(InvalidAttributes::class);

    expect($model->getErrors())
        ->toBeInstanceOf(MessageBag::class)
        ->toHaveKey('foo');

    expect($model->getErrors()->get('foo')[0])
        ->toBe('The foo field is required.');
});
