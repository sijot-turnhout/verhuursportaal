<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * The `Articles` model represents an inventory article in the system.
 *
 * This model defines relationships with storage locations and article categories (labels),
 * allowing articles to be associated with specific storage locations and categorized by labels.
 *
 * @package App\Models
 */
final class Articles extends Model
{
    use HasFactory;

    /**
     * Guard the `id` field from mass assignment.
     *
     * This prevents the `id` from being manually assigned during mass-assignment operations.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Defines the relationship between an article and its storage location.
     *
     * An article belongs to a specific storage location, which is represented by the `Local` model.
     * This is a one-to-many relationship where each article has a foreign key linking it to a storage location.
     *
     * @return BelongsTo
     */
    public function storageLocation(): BelongsTo
    {
        return $this->belongsTo(Local::class);
    }

    /**
     * Defines the many-to-many relationship between articles and labels.
     *
     * Articles can be associated with multiple labels (categories) via a pivot table `inventory_articles_categories`.
     * This enables flexible categorization of articles in the inventory system.
     *
     * @return BelongsToMany
     */
    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(ArticleCategory::class, table: 'inventory_articles_categories');
    }
}
