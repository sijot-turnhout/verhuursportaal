<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<self, \App\Models\Utility>
 *
 * @template TModel of \App\Models\Utility
 * @template TGet of mixed|null
 * @template TSet of mixed|null
 */
final class UtilityMetric implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  TModel  $model
     * @param  string $key
     * @param  mixed  $value
     * @param  array<string, mixed>  $attributes
     * @return mixed
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  TModel  $model
     * @param  string $key
     * @param  mixed   $value
     * @param  array<string, mixed>  $attributes
     * @return mixed
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return str_replace(',', '.', $value);
    }
}
