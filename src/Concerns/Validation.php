<?php

declare(strict_types=1);

namespace ArtisanSdk\Model\Concerns;

use ArtisanSdk\Model\Exceptions\InvalidAttributes;
use ArtisanSdk\Model\Observers\Validation as Observer;
use BadMethodCallException;
use Illuminate\Support\Arr;
use Watson\Validating\ValidatingTrait;

trait Validation
{
    /*
     * Use Watson's trait as a base.
     *
     * @see \Watson\Validating\ValidatingTrait
     */
    use ValidatingTrait;

    /**
     * Throw validation errors as exception.
     *
     * @var bool
     */
    public $throwValidationExceptions = true;

    /**
     * We want to boot our own observer so we stub out this boot method.
     *
     * This renders this function void.
     */
    public static function bootValidatingTrait()
    {
    }

    /**
     * Boot the trait's observers.
     */
    public static function bootValidation()
    {
        static::observe(new Observer());
    }

    /**
     * Get the validation rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Get the validation rules.
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules();
    }

    /**
     * Overload this method to prevent programmers from setting what can't be set.
     *
     * @param  array  $rules
     *
     * @throws BadMethodCallException
     */
    public function setRules(array $rules = null)
    {
        throw new BadMethodCallException(__FUNCTION__.'() is not allowed because getRules() is not property based.');
    }

    /**
     * Add rules to the existing rules, overriding any existing.
     *
     * @param  array  $rules
     */
    public function addRules(array $rules)
    {
        $newRules = array_merge($this->getRules(), $rules);
        $this->setRules($newRules);
    }

    /**
     * Remove rules from the existing rules.
     *
     * @param  mixed  $keys
     */
    public function removeRules($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();
        $rules = $this->getRules();
        Arr::forget($rules, $keys);
        $this->setRules($rules);
    }

    /**
     * Throw a validation exception.
     *
     * @throws \ArtisanSdk\Model\Exceptions\InvalidAttributes
     */
    public function throwValidationException($message = null)
    {
        $validator = $this->makeValidator($this->getRules());

        throw new InvalidAttributes($message, $validator, $this);
    }

    /**
     * Get the casted model attributes.
     *
     * @see \Watson\ValidatingTrait for default behavior.
     *
     * @return array
     */
    public function getModelAttributes()
    {
        $attributes = $this->getModel()->getAttributes();

        foreach ($attributes as $key => $value) {
            // The validator doesn't handle Carbon instances,
            // so instead of casting them we'll return their raw value instead.
            if (in_array($key, $this->getDates()) || $this->isDateCastable($key)) {
                $attributes[$key] = $value;

                continue;
            }

            // Cast the value.
            $attributes[$key] = $this->getModel()->getAttributeValue($key);
        }

        return $attributes;
    }
}
