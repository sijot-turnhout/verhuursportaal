<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * Defines a many-to-many relationship between the current model and the `Articles` model.
    *
    * This method assumes that there is a pivot table (usually named `article_category_article`
    * or something similar) that stores the relationship between the `ArticleCategory` model
    * and the `Articles` model. The relationship allows an article category to be associated
    * with multiple articles, and vice versa.
     *
     * @return BelongsToMany
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Articles::class, table: 'inventory_articles_categories');
    }
}
