<?php

declare(strict_types=1);

namespace ArtisanSdk\Model;

use ArtisanSdk\Model\Concerns\Validation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class Eloquent extends Model
{
    use HasFactory, Validation;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Exposed observable events.
     *
     * @var array
     */
    protected $observables = ['validating', 'validated'];

    /**
     * Get the validation rules.
     *
     * @return array
     */
    abstract public function rules(): array;
}
