<?php

namespace Dedemao\Payjs;

use Dcat\Admin\Extend\ServiceProvider;

use Dedemao\Payjs\Console\Commands\CreateMenu;
use Dedemao\Payjs\Console\Commands\DeleteMenu;
use Dedemao\Payjs\Console\Commands\Install;
use Dedemao\Payjs\Console\Commands\UnInstall;

class DcatAdminPayjsServiceProvider extends ServiceProvider
{
    protected $js = [
        'js/index.js',
    ];
    protected $css = [
        'css/index.css',
    ];

    protected $commands = [
        CreateMenu::class,
        DeleteMenu::class,
        Install::class,
        UnInstall::class,
    ];

    public function register()
    {
        $this->commands($this->commands);
    }

    public function init()
    {
        parent::init();

        if (file_exists($routes = base_path('routes/payjs_admin.php'))) {
            $this->loadRoutesFrom($routes);
        }

        if (file_exists($routes = base_path('routes/payjs_front.php'))) {
            $this->loadRoutesFrom($routes);
        }

        //if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database' => database_path(),
                __DIR__.'/../routes' => base_path('routes'),
            ], 'payjs-migrations');
        //}
    }

    public function settingForm()
    {
        return new Setting($this);
    }
}
