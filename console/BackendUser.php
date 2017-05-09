<?php namespace KosmosKosmos\AdminCLI\Console;

use Backend\Facades\BackendAuth;
use Backend\Models\UserGroup;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BackendUser extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'admincli:backenduser';

    /**
     * @var string The console command description.
     */
    protected $description = 'Manage Backend Users via CLI';

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $group = UserGroup::where('name', '=', $this->argument('group'))->first();
        $noPassword = false;

        if ($this->argument('password')) {
            $password = $this->argument('password');
        } else {
            $password = str_random(10);
            $noPassword = true;
        }

        $user = BackendAuth::findUserByLogin($name);
        if (!$user) {
            $user = BackendAuth::register([
                'first_name' => ucfirst($name),
                'last_name' => ucfirst($name),
                'login' => $name,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password
            ]);
        } else {
            $user->password = $password;
            $user->password_confirmation = $password;
        }

        if ($group) {
            $user->groups()->detach();
            $user->groups()->attach($group->id);
            
            if ($group->name == 'Owners') {
                $user->is_superuser = true;
            }
        }

        $user->save();

        if ($noPassword) {
            $this->output->writeln($password);
        }
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Username'],
            ['email', InputArgument::REQUIRED, 'Email'],
            ['group', InputArgument::OPTIONAL, 'User Group. Default value: Owner', 'Owners'],
            ['password', InputArgument::OPTIONAL, 'Password (optional). If password is not defined, it will be generated.'],
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
