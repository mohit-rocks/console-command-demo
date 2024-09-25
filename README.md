# console-command-demo
Demo application for the console command.

Presentation slides for the application can be found [here](https://github.com/mohit-rocks/symfony-console-drupal).

## How to use:
This repository is using Drupal core setup on DDEV.

Run following commands in-order to setup the site.

- ddev start
- ddev ssh
- ddev composer install
- Install the fresh Drupal site.

Once, you ssh, you can run the `dex` command.

### Running `dex` commands:

- `dex console_demo:service_argument`

### Code for the console command:

- `modules/custom/console_demo` contains the code for the console command.
