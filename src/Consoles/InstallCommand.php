<?php

namespace Tanwencn\Ecommerce\Consoles;

use Illuminate\Console\Command;
use Tanwencn\Blog\BlogServiceProvider;
use Tanwencn\Cart\CartServiceProvider;
use Tanwencn\Ecommerce\EcommerceServiceProvider;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecommerce:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Ecommerce';

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
        $this->call('vendor:publish', [
            '--provider' => CartServiceProvider::class
        ]);
        $this->call('vendor:publish', [
            '--provider' => EcommerceServiceProvider::class
        ]);

        $this->call('migrate');

        $this->call('ecommerce:registerPermissions');

        $this->info('Merge language pack complete.');

        $transFiles = app('files')->allFiles(__DIR__ . '/../../resources/lang');

        foreach ($transFiles as $file) {
            //dd(app('files')->getRequire($file));
            $langPath = resource_path("lang/{$file->getRelativePathname()}");

            $merg = app('files')->isFile($langPath) ? app('files')->getRequire($langPath) : [];

            $arr = app('files')->getRequire($file);

            app('files')->put($langPath, '<?php return ' . var_export(array_merge($merg, $arr), true) . ';');
        }
    }
}
