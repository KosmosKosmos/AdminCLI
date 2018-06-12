<?php namespace KosmosKosmos\AdminCLI\Console;

use Backend\Models\UserGroup;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BackendUserGroup extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'admincli:backendusergroup';

    /**
     * @var string The console command description.
     */
    protected $description = 'Manage Backend User Groups';

    /**
     * @deprecated
     */
    public function fire () {
        $this->handle();
    }
    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $group = UserGroup::where('name', '=', $this->argument('name'))->first();
        if (!$group) {
            $group = new UserGroup();
            $group->name = $this->argument('name');
            $group->code = $this->argument('code');
            $group->description = $this->argument('description');
        }

        if ($this->option('permissions')) {
            $group->permissions = array_fill_keys($this->option('permissions'), '1');
        }

        $group->save();
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Group name'],
            ['code', InputArgument::OPTIONAL, 'Group unique code'],
            ['description', InputArgument::OPTIONAL, 'Group description'],
        ];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['permissions', 'p', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Permissions (array)', []]
        ];
    }
}
