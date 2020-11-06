<?php

namespace Dedemao\Payjs\Console\Commands;

use Dcat\Admin\Models\Menu;
use Illuminate\Console\Command;

class UnInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payjs:uninstall {--m|migrate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '卸载payjs。删除数据表、去掉菜单等';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('payjs:deleteMenu');

        if ($this->option('migrate')) {
            $this->call('migrate:rollback', ['--force' => true]);
        }

        $this->info('payjs uninstall success');
    }
}
