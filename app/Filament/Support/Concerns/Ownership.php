<?php

declare(strict_types=1);

namespace App\Filament\Support\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait: Ownership
 *
 * This trait is solely responsible for checking the owner ship status of the database record.
 * We've implemented this traits to keep the code understanding an readability more fluent.
 * Is it more fluent then ->exist() or ->doesntExists()
 */
trait Ownership
{
    /**
     * Method that check if the given entity owns the related record in the system.
     *
     * @param  Model  $model  The model entity from the given database record
     * @param  string|null  $key  The key of the database key. Mostly it is the primary key of the database record;
     */
    public function owns(Model $model, ?string $key = null): bool
    {
        $key = $key ?: $this->getForeignkey();

        return $this->getKey() === $model->{$key};
    }

    /**
     * Method that check if the given entity doesn't own the related record in the system.
     *
     * @param  Model  $model  The model entity from the given database record
     * @param  string|null  $key  The key of the database key. Mostly it is the primary key of the database record;
     */
    public function doesntOwn(Model $model, ?string $key = null): bool
    {
        return ! $this->owns($model, $key);
    }
}
