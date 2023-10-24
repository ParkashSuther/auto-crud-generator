<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class newControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:controller {name} {--route=} {--var=* : List of variables (e.g., --var=name:string --var=age:int)}';
    // protected $signature = 'custom:controller {name} {--var=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a custom controller with modifications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $controllerName = $this->argument('name') . 'Controller';
        $modelName = $this->argument('name') . 'Model';
        $table_name = $this->argument('name');

        $viewFolder = $this->argument('name');
        $viewsPath = $this->argument('name');
        $variables = $this->option('var');
        $route = $this->option('route');

        $customMethods = ['index', 'create', 'store' ,'show', 'edit', 'update','destroy'];

        // Create the controller file
        $controllerPath = app_path('Http/Controllers/') . $controllerName . '.php';

        // Create the controller content
        $controllerContent = "<?php\n\n";
        $controllerContent .= "namespace App\\Http\\Controllers;\n\n";
        $controllerContent .= "use Illuminate\Http\Request;\n\n";
        $controllerContent .= "use App\Models\\$modelName;\n\n";
        $controllerContent .= "class $controllerName extends Controller\n";
        $controllerContent .= "{\n";

        // Add custom methods to the controller content
        foreach ($customMethods as $method) {
            if($method == 'index'){

                $controllerContent .= "    public function $method()\n";
                $controllerContent .= "    {\n";
                    $controllerContent .= "        try {\n";
                    $controllerContent .= "            // Custom logic for $method\n";
                    $controllerContent .= "            \$records = $modelName::get();\n";
                    $controllerContent .= "            return view('$viewFolder.index', get_defined_vars());\n\n";
                    $controllerContent .= "        } catch (\Throwable \$th) {\n";
                    $controllerContent .= "            throw \$th;\n";
                    $controllerContent .= "        }\n\n";

                $controllerContent .= "    }\n\n";

            } elseif ($method == 'show' || $method == 'edit') {
                $controllerContent .= "    public function $method(\$id)\n";
                $controllerContent .= "    {\n";
                    $controllerContent .= "        try {\n";
                    $controllerContent .= "            // Custom logic for $method\n";
                    $controllerContent .= "            \$record = $modelName::where('id', \$id)->first();\n";
                    $controllerContent .= "            if(!empty(\$record)) {\n";
                    $controllerContent .= "                return view('$viewFolder.$method', get_defined_vars());\n";
                    $controllerContent .= "            } else {\n";
                    $controllerContent .= "                abort(404);\n";
                    $controllerContent .= "            }\n";
                    $controllerContent .= "        } catch (\Throwable \$th) {\n";
                    $controllerContent .= "            throw \$th;\n";
                    $controllerContent .= "        }\n\n";

                $controllerContent .= "    }\n\n";
            } elseif ($method == 'create') {
                $controllerContent .= "    public function $method()\n";
                $controllerContent .= "    {\n";
                    $controllerContent .= "        try {\n";
                    $controllerContent .= "            // Custom logic for $method\n";
                    $controllerContent .= "            return view('$viewFolder.$method');\n";
                    $controllerContent .= "        } catch (\Throwable \$th) {\n";
                    $controllerContent .= "            throw \$th;\n";
                    $controllerContent .= "        }\n\n";

                $controllerContent .= "    }\n\n";
            } elseif ($method == 'store') {

                $controllerContent .= "    public function $method(Request \$request)\n";
                $controllerContent .= "    {\n";
                    $controllerContent .= "        try {\n";
                    $controllerContent .= "            // Custom logic for $method\n";
                    $controllerContent .= "            \$record = $modelName::create([\n";
                    // $controllerContent .= "                 \n";
                    foreach($variables as $variable) {
                        list($varName, $varType) = explode(':', $variable);
                        if($varType == 'int'){
                            $controllerContent .= "                '$varName' =>\$request->".$varName." ?? '0',\n";
                        }else {
                            $controllerContent .= "                '$varName' =>\$request->".$varName." ?? '',\n";
                        }
                    }
                    // $controllerContent .= "                 \n";
                    $controllerContent .= "            ]);\n\n";
                    $controllerContent .= "            if(!empty(\$record)) {\n";
                        if($route){
                            $controllerContent .= "                return redirect()->route('$route.index');\n";
                        } else {
                            $controllerContent .= "                return view('$viewFolder.$method', get_defined_vars());\n";
                        }
                    $controllerContent .= "            } else {\n";
                    $controllerContent .= "                abort(404);\n";
                    $controllerContent .= "            }\n";
                    $controllerContent .= "        } catch (\Throwable \$th) {\n";
                    $controllerContent .= "            //throw \$th;\n";
                    $controllerContent .= "        }\n\n";

                $controllerContent .= "    }\n\n";

            } elseif ($method == 'update') {

                $controllerContent .= "    public function $method(Request \$request, \$id)\n";
                $controllerContent .= "    {\n";
                    $controllerContent .= "        try {\n";
                    $controllerContent .= "            // Custom logic for $method\n";
                    $controllerContent .= "            \$record = $modelName::where('id', \$id)->update([\n";
                    // $controllerContent .= "                 \n";
                    foreach($variables as $variable) {
                        list($varName, $varType) = explode(':', $variable);
                        if($varType == 'int'){
                            $controllerContent .= "                '$varName' =>\$request->".$varName." ?? '0',\n";
                        }else {
                            $controllerContent .= "                '$varName' =>\$request->".$varName." ?? '',\n";
                        }
                    }
                    // $controllerContent .= "                 \n";
                    $controllerContent .= "            ]);\n\n";
                    $controllerContent .= "            if(!empty(\$record)) {\n";
                        if($route){
                            $controllerContent .= "                return redirect()->route('$route.index');\n";
                        } else {
                            $controllerContent .= "                return redirect()->route('$route.edit', \$id);";
                        }
                    $controllerContent .= "                return view('$viewFolder.edit', get_defined_vars());\n";
                    $controllerContent .= "            } else {\n";
                    $controllerContent .= "                abort(404);\n";
                    $controllerContent .= "            }\n";
                    $controllerContent .= "        } catch (\Throwable \$th) {\n";
                    $controllerContent .= "            //throw \$th;\n";
                    $controllerContent .= "        }\n\n";

                $controllerContent .= "    }\n\n";
            } elseif($method == 'destroy') {
                $controllerContent .= "    public function $method(\$id)\n";
                $controllerContent .= "    {\n";
                    $controllerContent .= "        try {\n";
                    $controllerContent .= "            // Custom logic for $method\n";
                    $controllerContent .= "            \$record = $modelName::where('id', \$id)->delete();\n";
                    $controllerContent .= "            if(!empty(\$record)) {\n";
                        if($route){
                            $controllerContent .= "                return redirect()->route('$route.index');\n";
                        } else {
                            $controllerContent .= "                return view('$viewFolder.index', get_defined_vars());\n";
                        }
                    $controllerContent .= "            } else {\n";
                    $controllerContent .= "                abort(404);\n";
                    $controllerContent .= "            }\n";
                    $controllerContent .= "        } catch (\Throwable \$th) {\n";
                    $controllerContent .= "            //throw \$th;\n";
                    $controllerContent .= "        }\n\n";

                $controllerContent .= "    }\n\n";
            } else {

                $controllerContent .= "    public function $method()\n";
                $controllerContent .= "    {\n";
                $controllerContent .= "        // Custom logic for $method\n";
                $controllerContent .= "    }\n\n";
            }

        }

        $controllerContent .= "}\n";

        // Write the controller content to the file
        File::put($controllerPath, $controllerContent);

        $this->info("Controller '$controllerName' created with custom methods: " . implode(', ', $customMethods));




        // Generate migration content and create the migration file
        $migrationContent = $this->generateMigrationContent($variables);
        $underscoredString = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $this->argument('name')));
        // $tableName = strtolower($underscoredString);
        $migrationFileName = date('Y_m_d_His') . "_create_{$underscoredString}_table.php";
        $migrationFilePath = database_path("migrations/{$migrationFileName}");
        File::put($migrationFilePath, $migrationContent);
        $this->info("Migration file '$underscoredString' created successfully.");


        // Generate model content and create the model file
        $modelContent = $this->generateModelContent($underscoredString, $modelName , $variables);
        $modelFileName = $modelName . '.php';
        $modelFilePath = app_path("Models/{$modelFileName}");
        File::put($modelFilePath, $modelContent);
        $this->info("Model file '$modelFileName' created successfully.");

        echo "Do you want to create relations? (y/n): ";
        $createRelations = trim(fgets(STDIN));

        if (strtolower($createRelations) === 'y') {
            $relations = [];

            while (true) {
                echo "Enter relation type (0 for belongsTo, 1 for hasOne, 2 for hasMany, q to quit): ";
                $relationType = trim(fgets(STDIN));

                if ($relationType === 'q') {
                    break;
                }

                echo "Enter foreign key name: ";
                $foreignKey = trim(fgets(STDIN));

                echo "Enter primary key name: ";
                $primaryKey = trim(fgets(STDIN));

                $relations[] = [
                    'type' => $relationType,
                    'foreign_key' => $foreignKey,
                    'primary_key' => $primaryKey,
                ];
            }

            // Process the relations
            foreach ($relations as $relation) {
                echo "Relation Type: {$relation['type']}, Foreign Key: {$relation['foreign_key']}, Primary Key: {$relation['primary_key']}\n";
                // Implement logic to handle each relation type and keys here
            }

            echo "Relations added successfully!\n";
        } else {
            echo "No relations created.\n";
        }


        // Generate Blade view content
        if (!empty($viewsPath)) {
            foreach ($customMethods as $method) {
                if($method == 'index'){
                    $bladeContent = $this->generateBladeContent_index($route, $variables); // Sample Blade template

                    $viewFilePath = resource_path("views/{$viewsPath}/{$method}.blade.php");
                    File::ensureDirectoryExists(dirname($viewFilePath));
                    File::put($viewFilePath, $bladeContent); // Create an empty Blade view file
                    $this->info("Blade view '$viewFilePath' created for method '$method'.");
                }
                if($method == 'create'){
                    $bladeContent = $this->generateBladeContent_create($route, $variables); // Sample Blade template

                    $viewFilePath = resource_path("views/{$viewsPath}/{$method}.blade.php");
                    File::ensureDirectoryExists(dirname($viewFilePath));
                    File::put($viewFilePath, $bladeContent); // Create an empty Blade view file
                    $this->info("Blade view '$viewFilePath' created for method '$method'.");
                }
                if($method == 'edit'){
                    $bladeContent = $this->generateBladeContent_edit($route, $variables); // Sample Blade template

                    $viewFilePath = resource_path("views/{$viewsPath}/{$method}.blade.php");
                    File::ensureDirectoryExists(dirname($viewFilePath));
                    File::put($viewFilePath, $bladeContent); // Create an empty Blade view file
                    $this->info("Blade view '$viewFilePath' created for method '$method'.");
                }
                if($method == 'show'){
                    $bladeContent = $this->generateShowBladeFile_show($route, $variables); // Sample Blade template

                    $viewFilePath = resource_path("views/{$viewsPath}/{$method}.blade.php");
                    File::ensureDirectoryExists(dirname($viewFilePath));
                    File::put($viewFilePath, $bladeContent); // Create an empty Blade view file
                    $this->info("Blade view '$viewFilePath' created for method '$method'.");
                }
            }
        }


        // $route = strtolower($this->argument('name'));
        $routeDefinition = "Route::resource('{$route}', {$controllerName}::class);";

        $webFilePath = base_path('routes/web.php');
        File::append($webFilePath, PHP_EOL . $routeDefinition);
        $this->info("Resource route added to web.php.");
    }

    private function castVariableType($value)
    {
        switch ($value) {
            case 'int':
            case 'integer':
                return (int) 0;
            case 'float':
                return (float) 0.0;
            case 'string':
                return '';
            case 'bool':
            case 'boolean':
                return false;
            default:
                // Handle other data types as needed
                return null;
        }
    }

    private function generateMigrationContent($variables)
    {
        // Generate migration content based on variables
        // You can customize the migration content according to your needs
        $underscoredString = preg_replace('/([a-z])([A-Z])/', '$1_$2', $this->argument('name'));
        $tableName = strtolower($underscoredString);
        $columns = [];
        foreach ($variables as $variable) {
            list($varName, $varType) = explode(':', $variable);
            $columns[] = "\$table->{$varType}('{$varName}');";
        }

        $migrationContent = "<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create{$this->argument('name')}Table extends Migration
{
    public function up()
    {
        Schema::create('{$tableName}', function (Blueprint \$table) {
            \$table->id();
            " . implode("\n            ", $columns) . "
            \$table->timestamps();
            \$table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('{$tableName}');
    }
}";

        return $migrationContent;
    }


    private function generateModelContent($name, $modelName , $variables)
    {
        // Generate model content with fillable attributes and table name
        $tableName = strtolower($name);
        $fillableAttributes = [];

        foreach ($variables as $variable) {
            list($varName, $varType) = explode(':', $variable);
            $fillableAttributes[] = "'{$varName}'";
        }

        $fillableString = implode(", ", $fillableAttributes);

        $modelContent = "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class {$modelName} extends Model
{
    use HasFactory, SoftDeletes;

    protected \$fillable = [{$fillableString}];

    protected \$table = '{$tableName}';
}
";

        return $modelContent;
    }

    // blade index file
    private function generateBladeContent_index($route, $variables)
    {
        // Generate Blade view content with a table to display data
        $bladeContent = '<h1>Data List</h1><a href="{{ route(\''.$route.'.create\') }}">Create New Record</a>';
        $bladeContent .= '<table border="1">';
        $bladeContent .= '<tr>';
        foreach ($variables as $variable) {
            list($varName, $varType) = explode(':', $variable);
            $bladeContent .= '<th>' . ucfirst($varName) . '</th>';
        }
        $bladeContent .= '<th>Action</th>';
        $bladeContent .= '</tr>';
        $bladeContent .= '@foreach($records as $item)';
        $bladeContent .= '<tr>';
        foreach ($variables as $variable) {
            list($varName, $varType) = explode(':', $variable);
            $bladeContent .= '<td>{{ $item->' . $varName . ' }}</td>';
        }
        $bladeContent .= '<td>';
        $bladeContent .= '<a href="{{ route(\''.$route.'.show\', $item->id) }}">Show</a> ';
        $bladeContent .= '<a href="{{ route(\''.$route.'.edit\', $item->id) }}">Edit</a>';
        $bladeContent .= '<form method="post" action="{{ route(\''.$route.'.destroy\', $item->id) }}">';
        $bladeContent .= '@csrf ';
        $bladeContent .= ' @method(\'DELETE\')';
        $bladeContent .= '<button type="submit">Delete</button>';
        $bladeContent .= ' </form>';
        $bladeContent .= '</td>';
        $bladeContent .= '</tr>';
        $bladeContent .= '@endforeach';
        $bladeContent .= '</table>';

        return $bladeContent;
    }

    private function generateBladeContent_create($route, $variables)
    {
        // Generate Blade view content with a form and a table to display data
        $bladeContent = '<h1>Data List</h1>';

        // Create form for adding new data
        $bladeContent .= '<h2>Add New Data</h2>';
        $bladeContent .= '<form method="post" action="{{ route(\''.$route.'.store\') }}">'; // Replace 'your.store.route' with the actual route for storing data
        $bladeContent .= '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        foreach ($variables as $variable) {
            list($varName, $varType) = explode(':', $variable);
            $bladeContent .= '<div>';
            $bladeContent .= '<label for="' . $varName . '">' . ucfirst($varName) . ':</label>';
            if ($varType === 'string') {
                $bladeContent .= '<input type="text" id="' . $varName . '" name="' . $varName . '">';
            } elseif ($varType === 'int' || $varType === 'integer') {
                $bladeContent .= '<input type="number" id="' . $varName . '" name="' . $varName . '">';
            } elseif ($varType === 'date') {
                $bladeContent .= '<input type="date" id="' . $varName . '" name="' . $varName . '">';
            } elseif ($varType === 'float') {
                $bladeContent .= '<input type="number" step="0.01" id="' . $varName . '" name="' . $varName . '">';
            } elseif ($varType === 'boolean' || $varType === 'bool') {
                $bladeContent .= '<input type="checkbox" id="' . $varName . '" name="' . $varName . '" value="1">';
            }
            $bladeContent .= '</div>';
        }
        $bladeContent .= '<button type="submit">Create</button>';
        $bladeContent .= '</form>';

        return $bladeContent;
    }

    private function generateBladeContent_edit($route, $variables)
    {
        $bladeContent = '<h2>Edit Data</h2>';
        $bladeContent .= '<form method="post" action="{{ route(\''.$route.'.update\', $record->id) }}">'; // Replace 'your.update.route' with the actual route for updating data
        $bladeContent .= '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        $bladeContent .= '<input type="hidden" name="_method" value="PUT">';
        foreach ($variables as $variable) {
            list($varName, $varType) = explode(':', $variable);
            $bladeContent .= '<div>';
            $bladeContent .= '<label for="' . $varName . '">' . ucfirst($varName) . ':</label>';
            if ($varType === 'string') {
                $bladeContent .= '<input type="text" id="' . $varName . '" name="' . $varName . '" value="{{ old(\'' . $varName . '\', $record->' . $varName . ') }}">';
            } elseif ($varType === 'date') {
                $bladeContent .= '<input type="date" id="' . $varName . '" name="' . $varName . '" value="{{ old(\'' . $varName . '\', $record->' . $varName . ') }}">';
            } elseif ($varType === 'int' || $varType === 'integer') {
                $bladeContent .= '<input type="number" id="' . $varName . '" name="' . $varName . '" value="{{ old(\'' . $varName . '\', $record->' . $varName . ') }}">';
            } elseif ($varType === 'float') {
                $bladeContent .= '<input type="number" step="0.01" id="' . $varName . '" name="' . $varName . '" value="{{ old(\'' . $varName . '\', $record->' . $varName . ') }}">';
            } elseif ($varType === 'boolean' || $varType === 'bool') {
                $bladeContent .= '<input type="checkbox" id="' . $varName . '" name="' . $varName . '" value="1" {{ old(\'' . $varName . '\', $record->' . $varName . ') ? \'checked\' : \'\' }}>';
            }
            $bladeContent .= '</div>';
        }
        $bladeContent .= '<button type="submit">Update</button>';
        $bladeContent .= '</form>';

        return $bladeContent;
    }

    private function generateShowBladeFile_show($route, $variables)
    {
        // Generate show.blade.php content based on variables
        $content = '<h1>Show Details</h1>';

        $content .= '<table border="1">';
        foreach ($variables as $variable) {
            list($varName, $varType) = explode(':', $variable);
            $content .= '<tr>';
            $content .= '<th>{{ ucfirst(\'' . $varName . '\') }}</th>';
            $content .= '<td>{{ $record->' . $varName . ' }}</td>';
            $content .= '</tr>';
        }
        $content .= '</table>';

        $content .= '<a href="{{ route(\''.$route.'.index\') }}">Back to List</a>';

        return $content;
    }
}
