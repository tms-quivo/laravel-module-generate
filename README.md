# Laravel Module Generator

Laravel Module Generator is a powerful package that helps you quickly generate files within Laravel modules using Artisan commands. This package streamlines your development process and ensures consistent code structure across your modules.

## Installation

Install the package via composer:

```bash
composer require tomosia-module/laravel-module-generate
```

## Usage

This package provides Artisan commands to quickly generate files within your Laravel modules. Here are some examples:

### Generate Controller

```bash
php artisan module:make-controller AuthController --module=User
```

This command will create a new controller file at `Modules/User/Http/Controllers/AuthController.php`

### Generate Request

```bash
php artisan module:make-request LoginRequest --module=User
```

This command will create a new request file at `Modules/User/Http/Request/LoginRequest.php`

## Available Commands

- `module:make-controller` - Generate a new controller in the module
- `module:make-request` - Generate a new request in the module

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
    └── ...
```
