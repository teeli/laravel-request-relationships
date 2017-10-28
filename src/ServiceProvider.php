<?php

namespace Aciddose\RequestRelationships;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            $this->configPath() => config_path($this->configFilename())
        ]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }


    protected function configPath()
    {
        return __DIR__ . '/../config/' . $this->configFilename();
    }

    protected function configFilename()
    {
        return 'requestrelationships.php';
    }

}
