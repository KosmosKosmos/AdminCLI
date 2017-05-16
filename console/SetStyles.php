<?php namespace KosmosKosmos\AdminCLI\Console;

use Backend\Models\BrandSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
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

        $settings = json_decode(DB::select('select value from system_settings where item = ?', ['backend_brand_settings'])[0]->value, true);

        if (ctype_xdigit($primary) && strlen($primary) == 6) {
            $settings['primary_color'] = '#'.$primary;
        }
        if (ctype_xdigit($secondary) && strlen($secondary) == 6) {
            $settings['secondary_color'] = '#'.$secondary;
        }
        if (ctype_xdigit($accent) && strlen($accent) == 6) {
            $settings['accent_color'] = '#'.$accent;
        }

        $settingsStr = json_encode($settings);

        DB::update('update system_settings set value = :settings where item = :item', ['settings' => $settingsStr, 'item' => 'backend_brand_settings']);
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
        ];
    }
}
