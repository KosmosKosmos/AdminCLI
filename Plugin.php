<?php namespace KosmosKosmos\AdminCLI;

use Backend;
use System\Classes\PluginBase;

/**
 * AdminCLI Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'AdminCLI',
            'description' => 'No description provided yet...',
            'author'      => 'KosmosKosmos',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConsoleCommand('admincli.backenduser', 'KosmosKosmos\AdminCLI\Console\BackendUser');
        $this->registerConsoleCommand('admincli.backendusergroup', 'KosmosKosmos\AdminCLI\Console\BackendUserGroup');
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'KosmosKosmos\AdminCLI\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'kosmoskosmos.admincli.some_permission' => [
                'tab' => 'AdminCLI',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'admincli' => [
                'label'       => 'AdminCLI',
                'url'         => Backend::url('kosmoskosmos/admincli/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['kosmoskosmos.admincli.*'],
                'order'       => 500,
            ],
        ];
    }
}
