# Auto CRUD Generator

### 1. Execute this command to initail
php artisan make:command CustomControllerCommand


### 2. Replace with given file on CustomControllerCommand.php in commands folder

### 3. Register the Custom Command:
     Register your custom Artisan command in the app/Console/Kernel.php file:
     
     > protected $commands = [
     >   \App\Console\Commands\CustomControllerCommand::class,
     > ];

### 4. Execute this command on terminal 
     > php artisan custom:controller Accounts --var=title:string --var=bank_id:integer --var=phone:string --var=email:string --var=status:integer --route=accounts

## Helps
     ### 1. var : var use to add column to database column this will also assing filds to blade, model, and migration with data type.
          
