<?php namespace KosmosKosmos\AdminCLI\Console;

use Backend\Models\BrandSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use October\Rain\Database\ModelException;
use October\Rain\Support\Facades\File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SetStyles extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'admincli:setstyles';

    /**
     * @var string The console command description.
     */
    protected $description = 'Manage Backend views style';

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        $primary = $this->option('primarycolor');
        $secondary = $this->option('secondarycolor');
        $accent = $this->option('accentcolor');
        $brand = $this->option('brandimage');

        if (ctype_xdigit($primary) && strlen($primary) == 6) {
            BrandSetting::set('primary_color', '#'.$primary);
        }
        if (ctype_xdigit($secondary) && strlen($secondary) == 6) {
            BrandSetting::set('secondary_color', '#'.$secondary);
        }
        if (ctype_xdigit($accent) && strlen($accent) == 6) {
            BrandSetting::set('accent-color', '#'.$accent);
        }
        if (File::exists($brand)) {
            File::copy($brand, storage_path('eventmanager/logo.'.pathinfo($brand)['extension']));
            $settings = BrandSetting::instance();
            $file = new \System\Models\File;
            $file->data = storage_path('eventmanager/logo.'.pathinfo($brand)['extension']);
            $file->is_public = true;
            $file->save();

            $settings->logo()->add($file);

        }

        Artisan::call('cache:clear');
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['primarycolor', 'p', InputOption::VALUE_REQUIRED, 'Primary color (HEX string, format: adbdef)', null],
            ['secondarycolor', 's', InputOption::VALUE_REQUIRED, 'Secondary color (HEX string, format: adbdef)', null],
            ['accentcolor', 'a', InputOption::VALUE_REQUIRED, 'Accent color (HEX string, format: adbdef)', null],
            ['brandimage', 'b', InputOption::VALUE_REQUIRED, 'Brand image', null],
        ];
    }
}
