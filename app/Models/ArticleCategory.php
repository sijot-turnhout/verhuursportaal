<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The ArticleCategory model represents a category for inventory articles in the system.
 *
 * This model is responsible for managing data related to different categories
 * that articles in the inventory can belong to. It uses Laravel's Eloquent ORM
 * for database interaction and includes factory support for testing and seeding.
 *
 * @package App\Models
 */
final class ArticleCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * If you want to specify which attributes can be mass assigned, you can define the `$fillable`
     * property. This prevents accidental assignment of unwanted attributes when using methods
     * like `create()` or `fill()`.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'description'];

    /**
     * Define a one-to-many relationship between ArticleCategory and Articles.
     *
     * This method establishes that one category can have multiple articles associated with it.
     * It uses Laravel's `hasMany` relationship to link the `ArticleCategory` model to
     * the `Articles` model. The relationship allows you to access all articles belonging
     * to a specific category.
     *
     * @return HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Articles::class);
    }
}
