<?php

namespace Dedemao\Payjs\Console\Commands;

use Dcat\Admin\Models\Menu;
use Illuminate\Console\Command;

class DeleteMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payjs:deleteMenu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '删除菜单';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Menu::query()->where('uri','like','payjs%')->delete();

        (new Menu())->flushCache();

        $this->info('menu delete success');
    }
}
