<?php

namespace App\Models;

use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Enums\ChangelogStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Changelog
 *
 * The changelog model represents a record (list) of changes or updates on the locals and inventory items in the system.
 * It includes information about follow-ups and the user responsible for those follow-ups.
 */
final class Changelog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'title', 'description'];

    /**
     * The default model attributes and their corresponding casts or value objects.
     *
     * This property defines the default attributes for the `Changelog` model.
     * Specifically, it casts the `status` attribute to an instance of the `ChangelogStatus` class,
     * which likely represents an enum or value object that encapsulates the possible states of a changelog.
     *
     * @var array<string, string>
     */
    protected $attributes = [
        'status' => ChangelogStatus::Open,
    ];

    /**
     * Defines the relationship bewteen the changelog and the User models.
     * This indicates that each changelog is associated with a user who is responsible for the follow-ups related to the changelog.
     *
     * @return BelongsTo  The relationship definition.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the custom casts for attributes in the model.
     *
     * This method should specifies how certain attributes of the 'Changelog' model should be cast or mutated.
     * The 'status' attribute is cast to an instance of the 'ChangelogStatus' class, allowing for easy  manipulation
     * and ensuring that the attribute is handled as a value object or enum.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ChangelogStatus::class,
        ];
    }
}
