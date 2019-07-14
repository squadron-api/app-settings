<?php

namespace Squadron\AppSettings;

use Squadron\AppSettings\Models\AppSettings;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
	    if ($this->app->runningInConsole())
	    {
            $this->publishes([
                __DIR__.'/../resources/lang/' => resource_path('lang/vendor/squadron.appSettings'),
            ], 'lang');

		    $this->publishes([
			    __DIR__ . '/../config/appSettings.php' => config_path('squadron/appSettings.php'),
		    ], 'config');
	    }

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang/', 'squadron.appSettings');
	    $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

	public function register(): void
	{
        $this->mergeConfigFrom(__DIR__.'/../config/appSettings.php', 'squadron.appSettings');

		$this->app->singleton(AppSettings::class);
    }
}
