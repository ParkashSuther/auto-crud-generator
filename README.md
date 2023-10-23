# Auto CRUD Generator

1. Execute this command to initail
php artisan make:command CustomControllerCommand


2. Replace with given file on CustomControllerCommand.php in commands folder

3. Register the Custom Command:
     Register your custom Artisan command in the app/Console/Kernel.php file:
     protected $commands = [
        \App\Console\Commands\CustomControllerCommand::class,
    ];
