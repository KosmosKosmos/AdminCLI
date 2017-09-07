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
        $colorPrimary = $this->option('primarycolor');
        $colorSecondary = $this->option('secondarycolor');
        $colorAccent = $this->option('accentcolor');
        $brandLogo = $this->option('brandimage');
        $tagline = $this->option('tagline');
        $appName = $this->option('appName');

        $branding = [];

        if (ctype_xdigit($colorPrimary) && strlen($colorPrimary) == 6) {
            $branding['primary_color'] = '#'.$colorPrimary;
        }
        if (ctype_xdigit($colorSecondary) && strlen($colorSecondary) == 6) {
            $branding['secondary_color'] = '#'.$colorSecondary;
        }
        if (ctype_xdigit($colorAccent) && strlen($colorAccent) == 6) {
            $branding['accent_color'] = '#'.$colorAccent;
        }
        if (strlen($tagline)) {
        	$branding['tagline'] = $this->option('tagline');
        }
        if (strlen($appName)) {
        	$branding['appName'] = $this->option('appName');
        }

        if (count($branding)) {
            BrandSetting::set($branding);
        }
        if (File::exists($brandLogo)) {
            $settings = BrandSetting::instance();
            $file = new \System\Models\File;
            $file->data = $brandLogo;
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
            ['tagline', 't', InputOption::VALUE_REQUIRED, 'App Tagline', null],
            ['appName', 'n', InputOption::VALUE_REQUIRED, 'App Name', null],
        ];
    }
}
