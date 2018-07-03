<?php

namespace Tanwencn\Ecommerce\Consoles;

class BootPermissionsCommand extends \Tanwencn\Blog\Consoles\BootPermissionsCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecommerce:registerPermissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register Permissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$this->info('Start to initialize permissions');
        // Reset cached roles and permissions
        /*app()['cache']->forget('spatie.permission.cache');

        $this->ability('dashboard');

        $this->ability('general_settings');

        $this->abilityResources('user');

        $this->abilityResources('role');

        $this->abilityResources('page');

        $this->abilityResources('post');

        $this->abilityResources('category');

        $this->abilityResources('tag');

        $this->abilityResources('advertising');

        $this->ability('menu');

        $this->ability('view_comment');

        $this->ability('edit_comment');

        $this->ability('delete_comment');

        $this->info('Initialize permissions is complete');*/

    }
}
