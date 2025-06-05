# Laravel Module Generator

Laravel Module Generator is a powerful package that helps you quickly generate files within Laravel modules using Artisan commands. This package streamlines your development process and ensures consistent code structure across your modules.

## Installation

Install the package via composer:

```bash
composer require tomosia-module/laravel-module-generate
```

## Configuration

Publish the package configuration file by running:

```bash
php artisan vendor:publish --tag=module-generator
```

## Usage

This package provides Artisan commands to quickly generate files within your Laravel modules. Here are some examples:

### Create a new module

```bash
php artisan module:create User
```

This command will create a new module at `Modules/User`

### Create a new container

```bash
php artisan container:create User
```

This command will create a new container at `App/Containers/User`


### Generate Controller

```bash
php artisan module:make-controller AuthController --module=User
```

This command will create a new controller file at `Modules/User/Http/Controllers/AuthController.php`

### Generate Request

```bash
php artisan module:make-request LoginRequest --module=User
```

This command will create a new request file at `Modules/User/Http/Requests/LoginRequest.php`

### Generate Action

```bash
php artisan module:make-action StoreAction --container=User
```

This command will create a new action file at `App/Containers/User/Actions/StoreAction.php`

## Available Commands

- `module:make-controller` - Generate a new controller in the module
- `module:make-request` - Generate a new request in the module
- `module:make-resource` - Generate a new resource in the module
- `module:make-provider` - Generate a new provider in the module
- `module:make-action` - Generate a new action in the container
- `module:make-model` - Generate a new model in the container
- `module:make-scope` - Generate a new scope in the container
- `module:make-repository` - Generate a new repository in the container
- `module:make-event` - Generate a new event in the container
- `module:make-listener` - Generate a new listener in the container
- `module:make-notification` - Generate a new notification in the container
- `module:make-policy` - Generate a new policy in the container
- `module:make-channel` - Generate a new channel in the container
- `module:make-job` - Generate a new job in the container
- `module:make-mail` - Generate a new mail in the container
- `module:make-observer` - Generate a new observer in the container

### Supports

- `module:make-livewire` - Generate a new livewire in the module
- `module:make-data` - Generate a new data in the container

## Features

- Quick generation of common Laravel module files
- Ensures consistent code structure
- Follows Laravel best practices
- Customizable templates
- Easy to extend with new commands

## Directory Structure

When using this package, files will be generated following the standard Laravel module structure:

```
Modules/
└── User/
    ├── Http/
    │   ├── Controllers/
    │   │   └── AuthController.php
    │   └── Requests/
    │       └── LoginRequest.php
    ├── Providers/
    │   └── UserServiceProvider.php
    ├── resources/
    │   ├── assets/
    │   ├── lang/
    │   ├── views/
    │   └── ...
    ├── routes/
    │   └── web.php
    └── ...
```

```
App/Containers
└── User/
    ├── Actions/
    │   └── ...
    ├── Events/
    │   └── ...
    ├── Listeners/
    │   └── ...
    ├── Models/
    │   └── ...
    ├── Notifications/
    │   └── ...
    ├── Policies/
    │   └── ...
    ├── Repositories/
    │   └── ...
    ├── Scopes/
    │   └── ...
    └── ...
```

## Contributing

Contributions are welcome! Please feel free to submit a pull request.
