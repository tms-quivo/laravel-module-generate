<?php

return [
	/*
	|--------------------------------------------------------------------------
	| Module Layer
	|--------------------------------------------------------------------------
	|
	| The path and namespace of the module layer.
	|
	*/
	'module_path' => 'Modules',
	'module_namespace' => 'Modules',

	/*
	|--------------------------------------------------------------------------
	| Container Layer
	|--------------------------------------------------------------------------
	|
	| The path and namespace of the container layer.
	|
	*/
	'container_path' => 'app/Containers',
	'container_namespace' => 'App\Containers',

	/*
	|--------------------------------------------------------------------------
	| Namespaces
	|--------------------------------------------------------------------------
	|
	| The namespaces of the module layer.
	|
	*/
	'namespaces' => [
		'action' => 'Actions',
		'channel' => 'Channels',
		'controller' => 'Http\Controllers',
		'event' => 'Events',
		'job' => 'Jobs',
		'mail' => 'Mails',
		'model' => 'Models',
		'listener' => 'Listeners',
		'notification' => 'Notifications',
		'observer' => 'Observers',
		'policy' => 'Policies',
		'provider' => 'Providers',
		'repository' => 'Repositories',
		'request' => 'Http\Requests',
		'resource' => 'Transformers',
		'resource_collection' => 'Transformers',
		'scope' => 'Scopes',
		'data' => 'Data',
	],

	/*
	|--------------------------------------------------------------------------
	| Folders
	|--------------------------------------------------------------------------
	|
	| The path and namespace of the module layer.
	|
	*/
	'c_scaffold_folders' => [
		'Actions',
        'Models',
        'Repositories',
        'Observers',
        'Events',
        'Listeners',
        'Jobs',
	],

	'm_scaffold_folders' => [
		'Http\Controllers',
		'Http\Requests',
		'Providers',
		'resources\assets',
		'resources\lang',
		'resources\views',
		'routes'
	],

	/*
	|--------------------------------------------------------------------------
	| Stubs
	|--------------------------------------------------------------------------
	|
	| The stubs for the module generator.
	|
	*/
	'stubs' => [
		'gitkeep' => false,
	],

	/*
	|--------------------------------------------------------------------------
	| Livewire
	|--------------------------------------------------------------------------
	|
	| The path and namespace of the livewire layer.
	|
	*/
	'livewire' => [
		'namespace' => 'Livewire',

		'view' => 'resources/views/livewire',
	]
];
