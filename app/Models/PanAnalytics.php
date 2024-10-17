<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * PanAnalytics Model
 *
 * This model stores and handles analytics data for a "pan" object. A pan could refer to a feature, UI component, or any trackable element in your application.
 * The model tracks various metrics such as impressions (views), hovers (mouse-overs), and clicks, along with calculated percentages.
 *
 * @package App\Models
 */
final class PanAnalytics extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pan_analytics';

    /**
     * Accessor for the 'name' attribute.
     *
     * This accessor transforms the name by replacing any hyphens ('-') with spaces (' ').
     *
     * @return Attribute<string, never>
     *
     */
    protected function name(): Attribute
    {
        return Attribute::make(get: fn (string $value): string => Str::replace('-', ' ', $value));
    }

    /**
     * Accessor for the 'impressions' attribute.
     *
     * This accessor formats the number of impressions (views) into a human-readable format using commas to separate thousands.
     *
     * @return Attribute<string, never>
     */
    protected function impressions(): Attribute
    {
        return Attribute::make(get: fn (int $value): string => toHumanReadableNumber($value));
    }

    /**
     * Accessor for the 'hovers' attribute.
     *
     * This accessor formats the number of hovers (mouse-overs) into a human-readable format using commas to separate thousands.
     *
     * @return Attribute<string, never>
     */
    protected function hovers(): Attribute
    {
        return Attribute::make(get: fn (int $value): string => toHumanReadableNumber($value));
    }

    /**
     * Accessor for calculating and returning the hover percentage.
     *
     * This method calculates the percentage of hovers over total impressions and formats the result as a human-readable percentage.
     * If there are zero impressions, it will return 'Infinity%' to handle division by zero.
     *
     * @return Attribute<string, never>
     */
    public function hoversPercentage(): Attribute
    {
        return Attribute::make(fn (): string => toHumanReadablePercentage($this->impressions, $this->hovers));
    }

    /**
     * Accessor for the 'clicks' attribute.
     *
     * This accessor formats the number of clicks into a human-readable format using commas to separate thousands.
     *
     * @return Attribute<string, never>
     */
    public function clicks(): Attribute
    {
        return Attribute::make(get: fn (int $value): string => toHumanReadableNumber($value));
    }
/**
     * Accessor for calculating and returning the clicks percentage.
     *
     * This method calculates the percentage of clicks over total impressions and formats the result as a human-readable percentage.
     * If there are zero impressions, it will return 'Infinity%' to handle division by zero.
     *
     * @return Attribute<string, never>
     */
    public function clicksPercentage(): Attribute
    {
        return Attribute::make(fn (): string => toHumanReadablePercentage($this->impressions, $this->clicks));
    }
}
