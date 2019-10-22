<?php
declare(strict_types=1);

namespace Rugaard\Trustpilot\Providers\Laravel;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Rugaard\Trustpilot\Trustpilot;

/**
 * Class ServiceProvider
 *
 * @package Rugaard\Trustpilot\Providers\Laravel
 */
class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Boot service provider.
     *
     * @return void
     */
    public function boot() : void
    {
        // Publish config file.
        $this->publishes([
            __DIR__ . '/config.php' => config_path('trustpilot.php'),
        ], 'trustpilot');

        // Use package configuration as fallback
        $this->mergeConfigFrom(
            __DIR__ . '/config.php', 'trustpilot'
        );
    }

    /**
     * Register service provider.
     *
     * @return void
     */
    public function register() : void
    {
        $this->app->singleton('rugaard.trustpilot', function ($app) {
            // Get configuration.
            $config = config('trustpilot');

            // Instantiate Trustpilot.
            $trustpilot = new Trustpilot(
                $config['apiKey'],
                $config['apiSecret'],
                $config['redirectUrl'] ?? null,
                $config['credentials']['username'] ?? null,
                $config['credentials']['password'] ?? null
            );

            if (!empty($config['businessUnitId'])) {
                $trustpilot->setBusinessUnitId($config['businessUnitId']);
            }

            return $trustpilot;
        });

        $this->app->bind(Trustpilot::class, function ($app) {
            return $app['rugaard.trustpilot'];
        });
    }
    /**
     * Get the services provided by this provider.
     *
     * @return array
     */
    public function provides() : array
    {
        return ['rugaard.trustpilot'];
    }
}
