<?php

define('ENGINE_NAME', 'LumenitePHP');
define('PROGRAM_NAME', 'lumenite');

// FILE CONTENTS:
define('LMNPHP_FILE_CONTENTS', [
    "LMN_App" => <<< LMN_App
<?php
// App Core goes here
LMN_App,
    "LMN_Controller" => <<< LMN_Controller
<?php
// Controller Core goes here
LMN_Controller,
    "LMN_Database" => <<< LMN_Database
<?php
// Database Core goes here
LMN_Database,
    "LMN_Model" => <<< LMN_Model
<?php
// Model Core goes here
LMN_Model,
    "LMN_Middleware" => <<< LMN_Middleware
<?php
// Middleware Core goes here
LMN_Middleware,
    "LMN_Router" => <<< LMN_Router
<?php
// Router Core goes here
LMN_Router,
    "LMN_View" => <<< LMN_View
<?php
// Views Core goes here
LMN_View,


    // WELCOME FILE CONTENTS
    "WelcomeModel" => <<< WelcomeModel
WelcomeModel,
    "WelcomeController" => <<< WelcomeController
WelcomeController,
    "WelcomeView" => <<< WelcomeView
WelcomeView,
    "WelcomeComp" => <<< WelcomeComp
WelcomeComp,
"WelcomeDBSchema" => <<< WelcomeDBSchema
WelcomeDBSchema,

"DefaultRoutes" =>  <<< DefaultRoutes
DefaultRoutes,
"DefaultCSS" =>  <<< DefaultCSS
DefaultCSS,
"DefaultJS" =>  <<< DefaultJS
DefaultJS,
"EntryPoint" =>  <<< EntryPoint
EntryPoint,
"DBPHPCONFIG" =>  <<< DBPHPCONFIG
DBPHPCONFIG,
"APPPHPCONFIG" =>  <<< APPPHPCONFIG
APPPHPCONFIG,
"CONFIGFILEDATA" =>  <<< CONFIGFILEDATA
<?php

// Load config.ini file
\$configFile = __DIR__ . '/config.ini';
if (!file_exists(\$configFile)) {
    die('Configuration file not found!');
}

// Parse the config.ini file into an associative array
\$config = parse_ini_file(\$configFile, true);

// Convert configuration values into PHP constants or variables for the app
define('APP_NAME', \$config['General Settings']['app_name']);
define('APP_VERSION', \$config['General Settings']['app_version']);
define('APP_ENV', \$config['General Settings']['environment']);
define('APP_DEBUG', filter_var(\$config['General Settings']['debug'], FILTER_VALIDATE_BOOLEAN));
define('DISPLAY_ERRORS', filter_var(\$config['General Settings']['display_errors'], FILTER_VALIDATE_BOOLEAN));

define('HOST', \$config['Server Settings']['host']);
define('PORT', \$config['Server Settings']['port']);

define('VIEW_ENGINE', \$config['View Engine Configuration']['view_engine']);

define('DB_HOST', \$config['Database Configuration']['db_host']);
define('DB_PORT', \$config['Database Configuration']['db_port']);
define('DB_NAME', \$config['Database Configuration']['db_name']);
define('DB_USER', \$config['Database Configuration']['db_user']);
define('DB_PASSWORD', \$config['Database Configuration']['db_password']);

define('CACHE_ENABLED', filter_var(\$config['Caching and Storage Settings']['cache_enabled'], FILTER_VALIDATE_BOOLEAN));
define('CACHE_DRIVER', \$config['Caching and Storage Settings']['cache_driver']);
define('STORAGE_PATH', \$config['Caching and Storage Settings']['storage_path']);

define('LOG_ENABLED', filter_var(\$config['Logging Settings']['log_enabled'], FILTER_VALIDATE_BOOLEAN));
define('LOG_LEVEL', \$config['Logging Settings']['log_level']);

define('APP_KEY', \$config['Security Settings']['app_key']);

?>

CONFIGFILEDATA,

// Database config

]);

