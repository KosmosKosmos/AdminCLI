<?php namespace KosmosKosmos\AdminCLI\Console;

use Backend\Facades\BackendAuth;
use Backend\Models\UserGroup;
use Backend\Models\UserRole;
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
     * @deprecated
     */
    public function fire() {
        $this->handle();
    }
    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        if ((!count($this->argument()) && !count($this->option())) || !$this->argument('name') || !$this->argument('email')) {
            $this->info('Create Backend User');
            $name = $this->ask('Enter username');
            $email = ' ';
            while (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email = $this->ask('Enter email address (must be valid)');
            }
            $groupName = ' ';
            $group = UserGroup::where('name', '=', $groupName)->first();
            $groupNames = UserGroup::all()->lists('name');
            while (!$group) {
                $groupName = $this->anticipate('Enter group name', $groupNames);
                $group = UserGroup::where('name', '=', $groupName)->first();
            }
            $noPassword = false;
            $password = null;
            $passwordConfirmation = null;

            while ($password == null || $password !== $passwordConfirmation) {
                $password = $this->secret('Enter password');
                $passwordConfirmation = $this->secret('Confirm password');
            }

        } else {
            $name = $this->argument('name');
            $email = $this->argument('email');
            $group = UserGroup::where('name', '=', $this->argument('group'))->first();
            if (class_exists('Backend\Models\UserRole')) {
                $role = UserRole::where('name', '=', $this->argument('group'))->first();
            }

            $noPassword = false;
            if ($this->argument('password')) {
                $password = $this->argument('password');
            } else {
                $password = $this->generatePassword();
                $noPassword = true;
            }
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

        if (isset($role) && $role !== null) {
            $user->role = $role;
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
                ['name', InputArgument::OPTIONAL, 'Username'],
                ['email', InputArgument::OPTIONAL, 'Email'],
                ['password', InputArgument::OPTIONAL, 'Password (optional). If password is not defined, it will be generated.'],
                ['group', InputArgument::OPTIONAL, 'User Group. Default value: Owners', 'Owners'],
                ['role', InputArgument::OPTIONAL, 'User Role. For new versions of October CMS only. Default value: Owners', 'Owners'],
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

    protected function generatePassword() {
        $password = '';
        $characters = [
            'ABCDEFGHJKLMNPQRSTUVWXYZ',
            'abcdefghjkmnpqrstuvwxyz',
            '0123456789',
            '~!@#$%^&*(){}[],./?'
        ];

        foreach ($characters as $set) {
            $password .= $set[array_rand(str_split($set))];
        }
        while(strlen($password) < 10) {
            $set = $characters[array_rand($characters)];
            $password .= $set[array_rand(str_split($set))];
        }

        return str_shuffle($password);
    }

}
