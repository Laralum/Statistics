<?php

namespace Laralum\Statistics;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laralum\Permissions\PermissionsChecker;
use Laralum\Statistics\Models\View;
use Laralum\Statistics\Policies\ViewPolicy;

class StatisticsServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        View::class => ViewPolicy::class,
    ];

    /**
     * The mandatory permissions for the module.
     *
     * @var array
     */
    protected $permissions = [
        [
            'name' => 'Statistics Access',
            'slug' => 'laralum::statistics.access',
            'desc' => 'Grants access to laralum/statistics module',
        ],
        [
            'name' => 'Restart Statistics',
            'slug' => 'laralum::statistics.restart',
            'desc' => 'Grants permission to restart statistics',
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->loadViewsFrom(__DIR__.'/Views', 'laralum_statistics');
        $this->loadTranslationsFrom(__DIR__.'/Translations', 'laralum_statistics');

        if (!$this->app->routesAreCached()) {
            require __DIR__.'/Routes/web.php';
        }

        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        // Make sure the permissions are OK
        PermissionsChecker::check($this->permissions);
    }

    /**
     * I cheated this comes from the AuthServiceProvider extended by the App\Providers\AuthServiceProvider.
     *
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
