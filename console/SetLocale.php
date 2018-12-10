<?php namespace KosmosKosmos\AdminCLI\Console;

use Illuminate\Console\Command;
use RainLab\Translate\Models\Locale;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SetLocale extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'admincli:setlocale';

    /**
     * @var string The console command description.
     */
    protected $description = 'Adds locale to Rainlab Translate Locales table';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        if ($this->option('clearall')) {
            Locale::truncate();
        }
        $locale = Locale::where('code', '=', $this->argument('code'))->first();
        if (!$locale) {
            $locale = new Locale();
        }
        $locale->code = $this->argument('code');
        $locale->name = ($this->argument('name') ? $this->argument('name') : $this->argument('code'));
        $locale->is_default = ($this->option('default') ? true : false);
        $locale->is_enabled = true;
        $locale->save();

        $this->output->writeln('Added locale "'.$locale->code.'", "'.$locale->name.'".'.($locale->is_default ? ' Set as default.' : ''));
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['code', InputArgument::REQUIRED, 'Code'],
            ['name', InputArgument::OPTIONAL, 'Name'],
        ];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['default', 'd', InputOption::VALUE_NONE, 'Make locale default'],
            ['clearall', 'c', InputOption::VALUE_NONE, 'Clear all existing locales before adding one']
        ];
    }
}
