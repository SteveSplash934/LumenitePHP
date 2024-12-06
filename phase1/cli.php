#!/usr/bin/php
<?php

require_once(__DIR__ . '/engine.constant.php');

class CLI
{
    private $CLI_COMMANDS = array(
        "create" => "create",
        "config" => "",
        "init" => "",
        "serve" => "",
        "build" => "",
        "watch" => "",
        "seed" => "",
        "test" => "",
        "deploy" => "",
        "rollback" => "",
        "status" => "",
        "version" => "",
        "help" => "",
        "info" => "",
        "clear" => "",
        "optimize" => "",
        "update" => "",
        "logs" => "",
        "debug" => "",
        "validate" => "",
        "lint" => "",
        "key" => "",
        "key:generate" => "",
        "cert" => "",
        "cert:generate" => "",
        "migrate" => "",
        "migrate:rollback" => "",
        "migrate:reset" => "",
        "m" => ""
    );


    private $argv;
    private $argc = 0;

    public function __construct($argv, $argc)
    {
        $this->argv = $argv;
        $this->argc = $argc;
        $cmd = (string) ($this->argv[1] ?? null);

        if ($this->argc != 1) {
            if (!$this->command_exists($cmd)) {
                echo "Command '" . $cmd . "' doesn't exists!\n";
                return;
            }

            $cmd = $this->getCmdFromArgv();
            $params = $this->getParamFromArgv();
            $this->call_command_callback($cmd, [$params]);

            // echo $cmd . " " . $params;


        } else {
            echo ENGINE_NAME . " expected a command! Try running with: " . PROGRAM_NAME . " help\n";
            return;
        }
    }

    public function getCmdFromArgv()
    {
        return (string) ($this->argv[1] ?? null);
    }
    public function getParamFromArgv()
    {
        return array_slice($this->argv, 2);
    }

    public function getArgvParam($p)
    {
        return $this->argv[$p];
    }

    public function command_exists($cmd)
    {
        return (array_key_exists($cmd, $this->CLI_COMMANDS)) ? true : false;
    }
    public function call_command_callback($cmd, $params = [])
    {
        $function_name = $this->get_command_callback($cmd);
        if ($function_name) {
            return (empty($params)) ? $this->$function_name() : $this->$function_name(...$params);
        } else {
            return null;
        }
    }
    public function get_command_callback($cmd)
    {
        if (!$this->command_exists($cmd)) {
            echo "Command '" . $cmd . "' doesn't exists!";
            return null;
        }
        return $this->CLI_COMMANDS[$cmd];
    }
    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return false;
        }

        // Open the directory
        $items = scandir($dir);

        foreach ($items as $item) {
            // Skip the special entries "." and ".."
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            // If it's a directory, recurse into it; otherwise, delete the file
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }

        // Remove the directory itself
        return rmdir($dir);
    }

    private function createProjectStructure($baseDir, $structure)
    {
        foreach ($structure as $name => $content) {
            $path = $baseDir . DIRECTORY_SEPARATOR . $name;
            if (is_array($content)) {
                // Create directory
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                // Recursively create sub-structure
                $this->createProjectStructure($path, $content);
            } else {
                // Create file
                if (!file_exists($path)) {
                    file_put_contents($path, $content);
                }
            }
        }
    }


    public function create($params = [], $overwrite = false)
    {
        $this->banner();
        if (!empty($params)) {
            if (file_exists((string) $params[0]) && !$overwrite) {
                echo "Directory '" . $params[0] . "' already exists, do you want to overwrite? [Y/n]: ";
                (string) $answer = readline();
                if (trim(strtolower($answer)) == "y" || trim(strtolower($answer)) == "yes") {
                    $this->create($params, $overwrite = true);
                } else {
                    echo "Project aborted!\n";
                }
            } else {
                if (file_exists($params[0]) && is_dir($params[0]) && $overwrite == true) {
                    echo "Deleting previous app...";
                    if (!$this->deleteDirectory($params[0])) {
                        echo "\nError occurred while deleting app, please try again.\n";
                        exit();
                    }
                    echo "Done!\n";
                    $this->create($params, $overwrite = true);
                } elseif (file_exists($params[0]) && !is_dir($params[0])) {
                    echo "Project aborted! '" . $params[0] . "' is not a dir but exists as a file!\n";
                    exit();
                } else {
                    echo "Creating app: " . $params[0] . "...";
                    mkdir((string)$params[0]);
                    $projectStructure = [
                        "" . $params[0] . "" => [
                            "app" => [
                                "controllers" => [
                                    "Controllers.php" => "<?php\n// Boiler plate code for Controllers"
                                ],
                                "models" => [
                                    "Models.php" => "<?php\n// Boiler plate code for Models"
                                ],
                                "views" => [
                                    "Views.php" => "<?php\n// Boiler plate code for Views",
                                    "components" => [],
                                    "widgets" => []
                                ],
                                "middleware" => [
                                    "Middleware.php" => "<?php\n// Boiler plate code for Middleware"
                                ],
                                "database" => [
                                    "Database.php" => "<?php\n// Boiler plate code for Database"
                                ],
                                "components" => ["comp1.php" => "<?php //Example component goes here..."]
                            ],
                            "public" => [
                                "components" => [],
                                "css" => ["style.css" => "body{}"],
                                "js" => ["script.js" => "alert(1);"],
                                "images" => [],
                                "index.php" => "<?php\n// Entry point"
                            ],
                            "config" => [
                                "database.php" => "<?php\n// Database configurations",
                                "app.php" => "<?php\n// General app settings",
                                "routes.php" => "<?php\n// Route definitions"
                            ],
                            "storage" => [
                                "cache" => [],
                                "logs" => [],
                                "uploads" => []
                            ],
                            "tests" => [],
                            "vendor" => [],
                            ".env" => "APP_ENV=local\nAPP_DEBUG=true\nAPP_KEY=",
                            "composer.json" => "{\n    \"require\": {}\n}",
                            "lumenite.php" => "<?php\n// CLI entry point",
                            "config.conf" => "# General Settings\napp_name = \"LumeniteApp\"\napp_version = \"1.0.0\"\nenvironment = \"local\"  # local, production, etc.\ndebug = true           # Set to true for development, false for production\ndisplay_errors = true  # Show errors on screen\n\n# Server Settings\nhost = \"127.0.0.1\"\nport = 8000\n\n# View Engine Configuration\nview_engine = \"Renderize\"  # Options: \"Renderize\", \"Blade\", \"ReactJS\", \"Vue\", \"vanilla\"\n\n# Database Configuration (Example for future DB integration)\ndatabase_engine = \"mysql\"  # Options: \"mysql\", \"sqlite\", etc.\ndb_host = \"localhost\"\ndb_port = \"3306\"\ndb_name = \"lumenite_db\"\ndb_user = \"root\"\ndb_password = \"\"\n\n# Caching and Storage Settings\ncache_enabled = true\ncache_driver = \"file\"  # Options: \"file\", \"redis\", etc.\nstorage_path = \"storage\"\n\n# Logging Settings\nlog_enabled = true\nlog_level = \"debug\"  # Options: \"debug\", \"info\", \"error\", etc.\n\n# Security Settings\napp_key = \"your-secret-key\"  # Set a strong key for encryption (e.g., JWT)\n",
                            "README.md" => "# MyWebApp\nThis is a LumenitePHP project."
                        ]
                    ];

                    $baseDir = __DIR__; // You can change this to the desired root directory
                    $this->createProjectStructure($baseDir, $projectStructure);
                    echo "Done!\n";
                }
            }
        } else {
            echo "No parameters provided for 'create' command.\n";
        }
    }

    public function banner()
    {
        echo <<<BANNER
·······························································
: _                               _ _       ____  _   _ ____  :
:| |   _   _ _ __ ___   ___ _ __ (_) |_ ___|  _ \| | | |  _ \ :
:| |  | | | | '_ ` _ \ / _ \ '_ \| | __/ _ \ |_) | |_| | |_) |:
:| |__| |_| | | | | | |  __/ | | | | ||  __/  __/|  _  |  __/ :
:|_____\__,_|_| |_| |_|\___|_| |_|_|\__\___|_|   |_| |_|_|    :
·······························································

BANNER;
    }

    public function help()
    {
        $this->banner();
        echo <<<HELPTEXT
    
    > HELP
    --------------------------
    1. Project Setup & Configuration
       - create       : Initialize a new project with a specific template or structure.
       - config       : Set or view configuration settings (e.g., environment variables, DB settings).
       - init         : Initialize the LumenitePHP environment or configurations for the project.
    
    2. Project Development
       - serve        : Start a development server for the project.
       - build        : Build or compile assets for production (e.g., minify JS, CSS).
       - watch        : Watch for changes in project files and reload/recompile automatically.
       - migrate      : Run database migrations (if database is involved).
       - seed         : Seed the database with sample data.
       - test         : Run unit tests or integration tests for the project.
    
    3. Deployment
       - deploy       : Deploy the project to the server (can include staging or production).
       - rollback     : Roll back the last deployment to a previous state.
       - status       : Check the status of the deployment (whether the application is running or encountering issues).
    
    4. Utility & Information
       - version      : Display the current version of LumenitePHP.
       - help         : Display help information for available commands.
       - info         : Show system information (like PHP version, extensions, etc.).
    
    5. Maintenance & Cleanup
       - clear-cache  : Clear application cache, logs, or compiled files.
       - optimize     : Optimize the application (e.g., clear logs, optimize autoloaders).
       - update       : Update the framework or dependencies to their latest versions.
    
    6. Debugging & Logs
       - logs         : View logs (application logs, errors, or access logs).
       - debug        : Enable or display debug mode for development.
    
    7. Testing & Validation
       - validate     : Run validation on configuration files, routes, or models.
       - lint         : Lint the codebase for syntax or style errors.
    
    8. Security
       - key:generate : Generate application keys or cryptographic keys for security.
       - cert:generate : Generate SSL certificates for the application.
    
    9. Database & Migrations
       - migrate:rollback : Rollback the last batch of migrations.
       - migrate:reset    : Reset all migrations to a fresh state.
       - migrate:status   : Show the status of migrations (whether they've been run or not).
    HELPTEXT;
    }
}
$LumeniteCLI = new CLI($argv, $argc);
