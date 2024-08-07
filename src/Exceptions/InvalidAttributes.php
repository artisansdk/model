<?php

declare(strict_types=1);

namespace ArtisanSdk\Model\Exceptions;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Watson\Validating\ValidationException;

class InvalidAttributes extends ValidationException
{
    /**
     * Create a new validation exception instance.
     *
     * @param  string|null  $message
     * @param  Validator  $validator
     * @param  Model  $model
     */
    public function __construct(?string $message, Validator $validator, Model $model)
    {
        parent::__construct($validator, $model);

        $this->applyDefaultMessage($message);

        $this->expandMessageOnCosole($model);
    }

    /**
     * Render the exception as a JSON error.
     *
     * @return JsonResponse
     */
    public function render()
    {
        return new JsonResponse([
            'message' => $this->getMessage(),
            'errors' => $this->getErrors()->toArray(),
            'resource' => $this->resourceName(),
        ], 422);
    }

    /**
     * Apply the default message if no message is provided
     *
     * @param  string|null  $message
     * @return void
     */
    protected function applyDefaultMessage(?string $message = null): void
    {
        $this->message = $message ?? 'The '.$this->resourceName().' has invalid attributes.';
    }

    /**
     * Expand the message to clarify context when running in a console environment.
     *
     * @param  Model  $model
     * @return void
     */
    protected function expandMessageOnCosole(Model $model): void
    {
        $app = App::getFacadeApplication();

        if ($app && ($app->environment('testing') || $app->runningInConsole())) { // @phpstan-ignore-line
            $this->message .= sprintf(' %s', json_encode($this->getErrors(), JSON_PRETTY_PRINT));
            $this->message .= PHP_EOL.sprintf('The %s has the attributes: %s', $this->resourceName(), $model->makeVisible($model->getHidden())->toJson(JSON_PRETTY_PRINT));
        }
    }

    /**
     * Get the resource name from the model.
     *
     * @return string
     */
    protected function resourceName(): string
    {
        return strtolower(class_basename($this->model()));
    }
}
