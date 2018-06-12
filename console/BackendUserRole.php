<?php namespace KosmosKosmos\AdminCLI\Console;

use Backend\Models\UserRole;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BackendUserRole extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'admincli:backenduserrole';

    /**
     * @var string The console command description.
     */
    protected $description = 'Manage Backend User Roles';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $role = UserRole::where('name', '=', $this->argument('name'))->first();
        if (!$role) {
            $role = new UserRole();
            $role->name = $this->argument('name');
            $role->code = $this->argument('code');
            $role->description = $this->argument('description');
        }

        if ($this->option('permissions')) {
            $role->permissions = array_fill_keys($this->option('permissions'), '1');
        }

        $role->save();
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
