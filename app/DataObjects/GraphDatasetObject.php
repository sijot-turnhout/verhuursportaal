<?php

declare(strict_types=1);

namespace App\DataObjects;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

final class GraphDatasetObject extends Data
{
    /**
     * Create a new GraphDatasetObject instance.
     * This object encapsulates the dataset configuration for a graph. It holds the label for the dataset, the data points themselves, and styling information, including the background color, border color, and point background color.
     *
     * @param string                        $label                  The label for the dataset, used in the graph legend to identify this set of data.
     * @param Collection<int|string, mixed> $data                   A collection containing the data points. The collection keys can be integers or strings, and the values can be of any type.
     * @param string                        $backgroundColor        The background color for the dataset when rendered on the graph.
     * @param string                        $borderColor            The border color for the dataset when rendered on the graph.
     * @param string                        $pointBackgroundColor   The background color for the individual data points on the graph.
     */
    public function __construct(
        public readonly string $label,
        public readonly Collection $data,
        public readonly string $backgroundColor,
        public readonly string $borderColor,
        public readonly string $pointBackgroundColor,
    ) {}
}
