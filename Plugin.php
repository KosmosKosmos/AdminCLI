<?php namespace KosmosKosmos\AdminCLI;

use System\Classes\PluginBase;

class Plugin extends PluginBase {

    public function pluginDetails() {
        return [
            'name'        => 'AdminCLI',
            'description' => 'A collection of Artisan console commands for October CMS administration',
            'author'      => 'KosmosKosmos',
            'icon'        => 'icon-wrench'
        ];
    }

    public function register() {
        $this->registerConsoleCommand('admincli.backenduser', 'KosmosKosmos\AdminCLI\Console\BackendUser');
        $this->registerConsoleCommand('admincli.backendusergroup', 'KosmosKosmos\AdminCLI\Console\BackendUserGroup');
        $this->registerConsoleCommand('admincli.setstyles', 'KosmosKosmos\AdminCLI\Console\SetStyles');
        $this->registerConsoleCommand('admincli.frontendusergroup', 'KosmosKosmos\AdminCLI\Console\FrontendUserGroup');
        $this->registerConsoleCommand('admincli.backenduserrole', 'KosmosKosmos\AdminCLI\Console\BackendUserRole');
        $this->registerConsoleCommand('admincli.setlocale', 'KosmosKosmos\AdminCLI\Console\SetLocale');
    }

}
