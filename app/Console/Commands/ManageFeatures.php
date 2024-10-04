<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Laravel\Pennant\Feature;

/**
 * This command is used to manage feature flags in the application.
 * You can enable, disable, or check the status of a feature using this command.
 *
 * Usage: `php artisan feature:manage {action} {feature}`
 *
 * @todo Implement and phase out the old feature configuration from the project config file.
 *
 * @see https://github.com/sijot-turnhout/developer-documentation/blob/main/console-commands/feature-commando.md
 *
 * @package App\Console\Commands
 */
final class ManageFeatures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * {action} - The action to perform: enable, disable, or check the status of a feature.
     * {feature} - The feature you want to manage (e.g., a feature flag name).
     *
     * @var string
     */
    protected $signature = 'feature:manager {action} {feature}';

    /**
     * The console command description.
     *
     * This description will appear when you run `php artisan list` to show
     * what this command does.
     *
     * @var string
     */
    protected $description = 'Enable, disable or check the status of a feature flag';

    /**
     * Execute the console command.
     *
     * This method is the entry point when the command is called.
     * It retrieves the action (enable, disable, status) and the feature name
     * from the command arguments, then performs the requested action.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = $this->argument('action');
        $feature = $this->argument('feature');

        // Resolve the fully qualified class name for the feature.
        $fullyQualifiedFeatureClass = $this->resolveFeatureClass($feature);

        // If the feature class doesn't exist, show an error and exit
        if ( ! class_exists($fullyQualifiedFeatureClass)) {
            $this->error("The feature class '{$fullyQualifiedFeatureClass}' does not exist.");
            return;
        }

        // Match the action and call the corresponding method.
        match ($action) {
            'enable' => $this->enableFeature($fullyQualifiedFeatureClass),
            'disable' => $this->disableFeature($fullyQualifiedFeatureClass),
            'status' => $this->featureStatus($fullyQualifiedFeatureClass),
            default => $this->error('Invalid action. Use "enable", "disable", or "status"'),
        };

        // Clear the cache after the feature status is changed.
        Feature::flushCache();
    }

    protected function enableFeature(string $feature, ?string $scope = null): void
    {
        Feature::activateForEveryone($feature);
        $this->info("Feature '{$feature}' enabled globally.");
    }

    /**
     * Enable a feature flag globally.
     *
     * This method activates the feature for everyone.
     *
     * @param  string      $feature  The fully qualified class name of the feature.
     * @param  string|null $scope    The scope for which to enable the feature (optional, defaults to global).
     * @return void
     */
    protected function disableFeature(string $feature): void
    {
        Feature::deactivateForEveryone($feature);
        $this->info("Feature '{$feature}' disabled globally.");
    }

    /**
     * Check the status of a feature flag.
     *
     * This method shows whether the feature is currently active or inactive.
     *
     * @param  string       $feature The fully qualified class name of the feature.
     * @param  string|null  $scope   The scope for which to check the feature status (optional, defaults to global).
     * @return void
     */
    protected function featureStatus(string $feature, ?string $scope = null): void
    {
        if (Feature::active($feature)) {
            $this->info("Feature '{$feature}' is active.");
        } else {
            $this->info("Feature '{$feature}' is inactive.");
        }
    }

    /**
     * Resolve the fully qualified class name for a feature.
     *
     * This method checks if the given feature class name includes the full namespace.
     * If not, it prepends the default 'App\\Features\\' namespace.
     *
     * @param  string $featureClass  The short or full class name of the feature.
     * @return string                The fully qualified class name of the feature.
     */
    private function resolveFeatureClass(string $featureClass): string
    {
        $featureNamespace = 'App\\Features\\';

        return Str::startsWith($featureClass, $featureNamespace)
            ? $featureClass
            : $featureNamespace . Str::studly($featureClass);
    }
}
