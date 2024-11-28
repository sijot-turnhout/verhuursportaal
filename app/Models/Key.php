<?php

declare(strict_types=1);

namespace App\Models;

use App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Enums\KeyTypes;
use App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Enums\MasterKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a Key, much like a physical key you'd use for a door.
 * This class defines how key information is stored and retrieved from the database.
 * It also describes how a Key relates to other things, like the User who has it and the Local (place) it unlocks.
 *
 * @package App\Models
 */
final class Key extends Model
{
    /**
     * This protected against accidentally overwriting the 'id' when creating or updating a key.
     * It's a security measure to ensure the key's unique identifier isn't modified.
     *
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * Defines the relationship between a key and the user who possesses it.
     * This allows us to easily find out who has a particular key.
     * A key belongs to a single user.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Defines the relationship between a key and the location (like a room or area) it unlocks.
     * This lets us determine which locations a key grants access to. A key belongs to a single location mainly.
     *
     * If there is no location assigned to this key or relationship. Then this key is registered as a master key.
     *
     * @return BelongsTo
     */
    public function local(): BelongsTo
    {
        return $this->belongsTo(Local::class);
    }

    /**
     * Tells the system how to handle specific key attributes.
     * For example, whether a key is a master key is stored as a special 'MasterKey' type,
     * and the key's production type is stored as a 'KeyTypes' type.
     *
     * @return array{is_master_key: class-string, type: class-string}
     */
    protected function casts(): array
    {
        return [
            'is_master_key' => MasterKey::class,
            'type' => KeyTypes::class,
        ];
    }
}
