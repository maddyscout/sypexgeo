<?php namespace Scriptixru\SypexGeo;

use Illuminate\Support\ServiceProvider;

class SypexGeoServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/sypexgeo.php' => config_path('sypexgeo.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register providers.
        $this->app->singleton('sypexgeo', function ($app) {
            $sypexConfig = $app['config'];
            $sypexConfigType = $sypexConfig->get('sypexgeo.sypexgeo.type', []);
            $sypexConfigPath = $sypexConfig->get('sypexgeo.sypexgeo.path', []);

            switch ($sypexConfigType) {
                case ('database'):
                    $sypexConfigFile = $sypexConfig->get('sypexgeo.sypexgeo.file', []);
                    $sxgeo = new SxGeo(base_path() . $sypexConfigPath . $sypexConfigFile);
                    break;
                case ('web_service'):
                    $license_key = $sypexConfig->get('sypexgeo.sypexgeo.license_key', []);
                    $sxgeo = new SxGeoHttp($license_key);
                    break;
                default:
                    $sypexConfigFile = $sypexConfig->get('sypexgeo.sypexgeo.file', []);
                    $sxgeo = new SxGeo(base_path() . $sypexConfigPath . $sypexConfigFile);
            }

            return new SypexGeo($sxgeo, $app['config']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['sypexgeo'];
    }

}
