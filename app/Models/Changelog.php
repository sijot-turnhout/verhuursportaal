<?php

namespace App\Models;

use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Enums\ChangelogStatus;
use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\States\ChangelogStateContract;
use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\States;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * Get the current state instance for the changelog.
     *
     * This method returns an instance of a state class that implements the `ChangelogStateContract`
     * interface, based on the current status of the changelog. The state object returned allows
     * for state-specific behavior and transitions to be managed according to the current status.
     *
     * @return ChangelogStateContract   The state object representing the current state of the changelog.
     */
    public function state(): ChangelogStateContract
    {
        return match($this->status) {
            ChangelogStatus::Open => new States\OpenChangelogState($this),
            ChangelogStatus::Closed => new States\ClosedChangelogState($this),
        };
    }

    /**
     * Define a many-to-many relationship between the `Changelog` and `Issue` models.
     *
     * This method establishes a BelongsToMany relationship, indicating that a single changelog
     * can be associated with multiple issues and vice versa. It returns the relationship definition
     * that can be used for querying and eager loading related issues.
     *
     * @return BelongsToMany
     */
    public function issues(): BelongsToMany
    {
        return $this->belongsToMany(Issue::class);
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
