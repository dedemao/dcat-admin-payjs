<?php

namespace Dedemao\Payjs\Console\Commands;

use Dcat\Admin\Admin;
use Dcat\Admin\Models\Menu;
use Illuminate\Console\Command;

class CreateMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payjs:createMenu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成菜单';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $createdAt = date('Y-m-d H:i:s');
        Menu::insert([
            [
                'parent_id'     => 0,
                'order'         => 10,
                'title'         => 'Payjs',
                'icon'          => 'fa-cny',
                'uri'           => 'payjs/order',
                'created_at'    => $createdAt,
            ]
        ]);

        (new Menu())->flushCache();


        $this->info('menu add success');
    }
}
