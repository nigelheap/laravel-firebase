<?php namespace Freelyformed\LaravelFirebase;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as GuzzleHttpClient;
use paragraph1\phpFCM\Client;

class FirebaseServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function boot() {
        $configPath = __DIR__ . '/../config/firebase.php';

        $publishPath = function_exists('config_path') ?
            config_path('firebase.php') :
            base_path('config/firebase.php');

        $this->publishes([$configPath => $publishPath], 'config');
    }

    public function register() {
        $this->mergeConfigFrom(config_path('firebase.php'), 'firebase');

        $this->app->singleton(Client::class, function() {
            $client = new Client();
            $client->setApiKey(config('firebase.key'));
            $client->injectHttpClient(new GuzzleHttpClient());
            return $client;
        });
    }
}