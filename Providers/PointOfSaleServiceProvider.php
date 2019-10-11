<?php

namespace Modules\PointOfSale\Providers;

use App\Providers\AuthServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class PointOfSaleServiceProvider extends AuthServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\PointOfSale\Models\PointOfSale::class => \Modules\PointOfSale\Policies\PointOfSalePolicy::class,
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();

        // register the commands to inject/eject the view
        $this->commands([
            \Modules\PointOfSale\Console\InjectScannerCommand::class,
            \Modules\PointOfSale\Console\EjectScannerCommand::class,
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('pointofsale.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'pointofsale'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = base_path('resources/views/modules/pointofsale');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/pointofsale';
        }, \Config::get('view.paths')), [$sourcePath]), 'pointofsale');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = base_path('resources/lang/modules/pointofsale');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'pointofsale');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang/en', 'pointofsale');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
