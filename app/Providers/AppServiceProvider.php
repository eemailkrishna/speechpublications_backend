<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Ensure Reverb configuration is properly set up before service provider registration
        $this->ensureReverbConfig();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Re-ensure Reverb config during boot
        $this->ensureReverbConfig();
    }

    /**
     * Ensure Reverb configuration has all required keys
     */
    private function ensureReverbConfig(): void
    {
        $reverbConfig = config('reverb', []);
        
        if (isset($reverbConfig['servers']['reverb'])) {
            $server = &$reverbConfig['servers']['reverb'];
            
            // Ensure scaling key exists
            if (!isset($server['scaling']) || !is_array($server['scaling'])) {
                $server['scaling'] = [
                    'enabled' => env('REVERB_SCALING_ENABLED', false),
                    'channel' => env('REVERB_SCALING_CHANNEL', 'reverb'),
                    'server' => [
                        'url' => env('REDIS_URL'),
                        'host' => env('REDIS_HOST', '127.0.0.1'),
                        'port' => env('REDIS_PORT', '6379'),
                        'username' => env('REDIS_USERNAME'),
                        'password' => env('REDIS_PASSWORD'),
                        'database' => env('REDIS_DB', '0'),
                        'timeout' => env('REDIS_TIMEOUT', 60),
                    ],
                ];
            }
            
            // Update the config
            $reverbConfig['servers']['reverb'] = $server;
            config(['reverb.servers.reverb' => $server]);
        }
    }
}

