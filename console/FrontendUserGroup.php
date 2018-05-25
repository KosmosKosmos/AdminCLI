<?php namespace KosmosKosmos\AdminCLI\Console;

use Illuminate\Console\Command;
use RainLab\User\Models\UserGroup;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FrontendUserGroup extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'admincli:frontendusergroup';

    /**
     * @var string The console command description.
     */
    protected $description = 'Manage Frontend User Groups';

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
	    $group = UserGroup::where('name', '=', $this->argument('name'))->first();
	    if (!$group) {
		    $group = new UserGroup();
		    $group->name = $this->argument('name');
		    $group->code = $this->argument('code');
		    $group->description = $this->argument('description');
		    $group->save();
	    }
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
	    return [
		    ['name', InputArgument::REQUIRED, 'Group name'],
		    ['code', InputArgument::REQUIRED, 'Group unique code'],
		    ['description', InputArgument::OPTIONAL, 'Group description'],
	    ];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
