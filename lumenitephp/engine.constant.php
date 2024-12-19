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

namespace App\Core;

use App\Core\View;

class Controller
{
    protected \$view;
    protected \$beforeMiddleware = [];
    protected \$afterMiddleware = [];
    protected \$viewData = [];

    public function __construct(\$view = new View())
    {
        // Initialize the View class
        \$this->view = \$view;
    }

    /**
     * Handle HTTP GET requests
     */
    public function get(\$action)
    {
        \$this->runBeforeMiddleware();

        if (method_exists(\$this, \$action)) {
            \$this->\$action();
        } else {
            \$this->handleError("Action \$action not found.");
        }

        \$this->runAfterMiddleware();
    }

    /**
     * Handle HTTP POST requests
     */
    public function post(\$action)
    {
        \$this->runBeforeMiddleware();

        if (method_exists(\$this, \$action)) {
            \$this->\$action();
        } else {
            \$this->handleError("Action \$action not found.");
        }

        \$this->runAfterMiddleware();
    }

    /**
     * Add a before middleware
     */
    public function addBeforeMiddleware(\$middleware)
    {
        \$this->beforeMiddleware[] = \$middleware;
    }

    /**
     * Add an after middleware
     */
    public function addAfterMiddleware(\$middleware)
    {
        \$this->afterMiddleware[] = \$middleware;
    }

    /**
     * Run before middleware
     */
    protected function runBeforeMiddleware()
    {
        foreach (\$this->beforeMiddleware as \$middleware) {
            \$middleware();
        }
    }

    /**
     * Run after middleware
     */
    protected function runAfterMiddleware()
    {
        foreach (\$this->afterMiddleware as \$middleware) {
            \$middleware();
        }
    }

    /**
     * Add a before action hook
     */
    public function before(\$callback)
    {
        \$this->addBeforeMiddleware(\$callback);
    }

    /**
     * Add an after action hook
     */
    public function after(\$callback)
    {
        \$this->addAfterMiddleware(\$callback);
    }

    /**
     * Invoke another action within the controller
     */
    public function action(\$name, \$params = [])
    {
        if (method_exists(\$this, \$name)) {
            call_user_func_array([\$this, \$name], \$params);
        } else {
            \$this->handleError("Action \$name not found.");
        }
    }

    /**
     * Redirect to a given URL
     */
    public function redirect(\$url)
    {
        header("Location: \$url");
        exit;
    }

    /**
     * Render a view
     */
    public function render(\$view, \$data = [])
    {
        \$data = array_merge(\$this->viewData, \$data);
        \$this->view->render(\$view, \$data);
    }

    /**
     * Set data to be passed to the view
     */
    public function setViewData(\$key, \$value)
    {
        \$this->viewData[\$key] = \$value;
    }

    /**
     * Set a flash message
     */
    public function setFlash(\$key, \$message)
    {
        \$_SESSION['flash'][\$key] = \$message;
    }

    /**
     * Get and clear a flash message
     */
    public function getFlash(\$key)
    {
        if (isset(\$_SESSION['flash'][\$key])) {
            \$message = \$_SESSION['flash'][\$key];
            unset(\$_SESSION['flash'][\$key]);
            return \$message;
        }
        return null;
    }

    /**
     * Validate form data based on rules
     */
    public function validate(\$data, \$rules)
    {
        \$errors = [];
        foreach (\$rules as \$field => \$rule) {
            if (empty(\$data[\$field]) && in_array('required', \$rule)) {
                \$errors[\$field][] = "\$field is required.";
            }
        }
        return \$errors;
    }

    /**
     * Authorize user based on their role
     */
    public function authorize(\$role)
    {
        if (!isset(\$_SESSION['user_role']) || \$_SESSION['user_role'] !== \$role) {
            \$this->redirect('/unauthorized');
        }
    }

    /**
     * Generate CSRF token
     */
    public function generateCsrfToken()
    {
        \$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return \$_SESSION['csrf_token'];
    }

    /**
     * Validate CSRF token
     */
    public function validateCsrfToken(\$token)
    {
        if (empty(\$token) || \$token !== \$_SESSION['csrf_token']) {
            \$this->handleError('Invalid CSRF token.');
        }
    }

    /**
     * Return a JSON response
     */
    public function jsonResponse(\$data, \$status = 200)
    {
        header('Content-Type: application/json');
        http_response_code(\$status);
        echo json_encode(\$data);
        exit;
    }

    /**
     * Handle errors
     */
    protected function handleError(\$message)
    {
        echo "Error: \$message";
        exit;
    }

    /**
     * Log system activity or errors
     */
    public function log(\$message)
    {
        file_put_contents('app.log', date('Y-m-d H:i:s') . " - \$message
", FILE_APPEND);
    }
}

LMN_Controller,
"LMN_Database" => <<< LMN_Database
<?php
namespace App\Core;
// Database class
class Database
{
    public function connect() {}
    public function query(\$sql, \$params = []) {}
    public function beginTransaction() {}
    public function commit() {}
    public function rollback() {}
    public function disconnect() {} // Added to disconnect from the database
    public function prepare(\$sql) {} // Added to prepare SQL queries
    public function lastInsertId() {} // Added to get the last inserted ID
    public function select(\$table, \$columns = "*") {} // Added to select records
}

LMN_Database,
"LMN_Request" => <<< LMN_Request
<?php
namespace App\Core;
// Request class
class Request
{
    public function getMethod() {}
    public function getUri() {}
    public function getHeaders() {}
    public function getQueryParams() {}
    public function getBody() {}
    public function getFiles() {}
    public function isMethod(\$method) {} // Added to check request method
    public function getParsedBody() {} // Added to get parsed body data
    public function getCookie(\$name) {} // Added to retrieve cookies
    public function getServer(\$key) {} // Added to get server variables
}
LMN_Request,
"LMN_Response" => <<< LMN_Response
<?php
namespace App\Core;
// Response class
class Response
{
    public function setStatusCode(\$code) {}
    public function setHeader(\$key, \$value) {}
    public function write(\$content) {}
    public function json(\$data) {}
    public function redirect(\$url, \$status = 302) {} // Added redirect method
    public function setCookie(\$name, \$value, \$expiry = 0, \$path = "/") {} // Added to set cookies
    public function download(\$filePath, \$fileName = null) {} // Added to handle file download
    public function setJson(\$data, \$status = 200) {} // Added to return JSON response
}
LMN_Response,
"LMN_Session" => <<< LMN_Session
<?php
namespace App\Core;
// Session class
class Session
{
    public function start() {}
    public function set(\$key, \$value) {}
    public function get(\$key) {}
    public function destroy() {}
    public function regenerate() {}
    public function getAll() {} // Added to get all session data
    public function has(\$key) {} // Added to check if session key exists
    public function remove(\$key) {} // Added to remove a session key
}
LMN_Session,
"LMN_Validator" => <<< LMN_Validator
<?php
namespace App\Core;
// Validator class
class Validator
{
    public function make(\$data, \$rules) {}
    public function fails() {}
    public function errors() {}
    public function passes() {} // Added to check if validation passes
    public function validate(\$field, \$rule) {} // Added to validate individual fields
    public function addRule(\$ruleName, \$ruleCallback) {} // Added to add custom validation rules
}
LMN_Validator,
"LMN_Logger" => <<< LMN_Logger
<?php
namespace App\Core;
// Logger class
class Logger
{
    public function log(\$level, \$message) {}
    public function info(\$message) {}
    public function warning(\$message) {}
    public function error(\$message) {}
    public function debug(\$message) {} // Added to log debug-level messages
    public function critical(\$message) {} // Added to log critical-level messages
    public function alert(\$message) {} // Added to log alert-level messages
}

LMN_Logger,
"LMN_Config" => <<< LMN_Config
<?php
namespace App\Core;
// Config class
class Config
{
    public function load(\$file) {}
    public function get(\$key) {}
    public function set(\$key, \$value) {}
    public function loadFromArray(\$configArray) {} // Added to load config from an array
    public function has(\$key) {} // Added to check if config key exists
    public function merge(\$array) {} // Added to merge configuration arrays
}

LMN_Config,
"LMN_Auth" => <<< LMN_Auth
<?php
namespace App\Core;
// Auth class
class Auth
{
    public function attempt(\$credentials) {}
    public function check() {}
    public function user() {}
    public function logout() {}
    public function register(\$data) {} // Added for user registration
    public function password(\$user, \$password) {} // Added to validate or update password
    public function forget() {} // Added to trigger password reset
    public function checkPermissions(\$permissions) {} // Added to check user permissions
}

LMN_Auth,
"LMN_Model" => <<< LMN_Model
<?php
namespace App\Core;
class Model
{
    public function find(\$id) {}
    public function findAll() {}
    public function where(\$column, \$value) {}
    public function save() {}
    public function delete() {}
    public function create(\$data) {} // Added to create a new record
    public function update(\$id, \$data) {} // Added to update a record
    public function destroy(\$id) {} // Added to destroy a record
    public function first() {} // Added to fetch the first record
    public function paginate(\$perPage = 10) {} // Added for pagination
}
LMN_Model,
"LMN_Middleware" => <<< LMN_Middleware
LMN_Middleware,
"LMN_Router" => <<< LMN_Router
<?php

namespace App\Core;

require_once __DIR__ . '/../../vendor/autoload.php';

class Router
{
    // Array to store routes
    private \$routes = [];
    private \$middleware = [];
    private \$beforeMiddleware = [];
    private \$afterMiddleware = [];
    private \$basePath = '';

    // Method to define a route (e.g., GET, POST)
    public function addRoute(\$method, \$uri, \$handler)
    {
        \$this->routes[] = [
            'method' => strtoupper(\$method),
            'uri' => \$uri,
            'handler' => \$handler,
        ];
    }

    // Method to match the request with the defined routes
    public function matchRoute(\$handler)
    {
        // Match request method and URI to a handler
        return list(\$controller, \$method) = explode('@', \$handler);
    }

    // Method to handle the request (invoke the route handler)
    public function dispatch(\$method, \$uri)
    {
        \$route = null;
        \$rhandler = null;

        // Ensure route exists
        if (!\$this->routeExists(\$method, \$uri)) {
            \$this->error('404');
        }

        // Get the handler for the route
        \$rhandler = \$this->getRouteHandler(\$method, \$uri);
        if (\$rhandler) {
            // Match the controller and method
            list(\$controller, \$method) = \$this->matchRoute(\$rhandler);

            // Build the full class name with the 'Controller' suffix
            \$controllerClass = '\\\App\\\Controller\\\' . \$controller . 'Controller';

            // Check if the class exists
            if (class_exists(\$controllerClass)) {
                \$controllerInstance = new \$controllerClass();

                // Check if the method exists
                if (method_exists(\$controllerInstance, \$method)) {
                    // Call the method dynamically
                    \$controllerInstance->\$method();
                } else {
                    // Handle method not found
                    \$this->error('Method not found');
                }
            } else {
                // Handle class not found
                echo "Class not detected!<br/>";
                \$this->error('404');
            }
        }
    }


    // Utility method to check if a route exists
    public function routeExists(\$method, \$uri)
    {
        // Check if a route exists for the given method and URI
        \$routes = \$this->getRoutes();
        foreach (\$routes as \$route) {
            if (\$route['method'] == \$method && \$route['uri'] == \$uri) {
                return true;
            }
        }
        return false;
    }

    public function getRouteHandler(\$method, \$uri)
    {
        foreach (\$this->routes as \$route) {
            if (\$route['method'] === strtoupper(\$method) && \$route['uri'] === \$uri) {
                return \$route['handler'];
            }
        }

        return null; // No route matched
    }
    // Method to get all defined routes (for debugging purposes)
    public function getRoutes()
    {
        return \$this->routes;
    }

    public function before(\$callback) {}
    public function after(\$callback) {}
    public function replaceParams(\$uri, \$params) {}
    public function group(\$prefix, \$routes)
    {
        foreach (\$routes as \$route) {
            \$this->addRoute(\$route['method'], \$prefix . \$route['uri'], \$route['action']);
        }
    }

    public function generateUrl(\$routeName, \$params = []) {}
    public function getCurrentUri()
    {
        return parse_url(\$_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
    public function getCurrentMethod()
    {
        return \$_SERVER['REQUESTED_METHOD'];
    }
    public function getFullUri()
    {
        return \$_SERVER['REQUESTED_URI'];
    }
    public function setBasePath(\$basePath)
    {
        \$this->basePath = rtrim(\$basePath, '/');
    }
    public function removeRoute(\$method, \$uri)
    {
        foreach (\$this->routes as \$key => \$route) {
            if (\$route['method'] === strtoupper(\$method) && \$route['uri'] === \$uri) {
                unset(\$this->routes[\$key]);
                return true;
            }
        }
        return false; // Route not found
    }
    public function error(\$error = '404', \$message = [])
    {
        \$accepted_errors = [
            '404' => '404 Not Found',
            '401' => '401 Unauthorized',
            '403' => '403 Forbidden',
            '500' => '500 Internal Server Error',
            '400' => '400 Bad Request',
        ];

        if (!array_key_exists(\$error, \$accepted_errors)) {
            return \$this->error('400'); // Default to "400 Bad Request"
        } else {
            header("HTTP/1.1 " . \$accepted_errors[\$error]);

            if (empty(\$message)) {
                // pass to controllers to send the views
                // Display the error message
                echo "<h1>Error: {\$error}</h1>";
                echo "<p>{\$accepted_errors[\$error]}</p>";

                // Stop further execution
            } else {
                \$title = \$message['title'] ?? \$error;
                \$msg = \$message['message'] ?? \$accepted_errors[\$error];

                echo "<h1>Error: {\$title}</h1>";
                echo "<p>{\$msg}</p>";
            }

            exit();
        }
    }

    public function applyBeforeMiddleware() {}
    public function applyAfterMiddleware() {}
    public function addMiddleware(\$middleware) {}
    public function validate(\$uri) {}
    public function fetchPOSTUri() {}
    public function fetchGETUri() {}
    public function isGet(\$uri) {}
    public function isPost() {}
    public function get(\$uri, \$callback) {}
    public function post(\$uri, \$callback) {}
    public function put(\$uri, \$callback) {}
    public function delete(\$uri, \$callback) {}
    public function patch(\$uri, \$callback) {} // Added PATCH method
    public function head(\$uri, \$callback) {} // Added HEAD method
    public function options(\$uri, \$callback) {} // Added OPTIONS method
    public function middleware(\$middleware) {} // Added Middleware method
}
LMN_Router,
"LMN_View" => <<< LMN_View
<?php

namespace App\Core;

class View
{
    // Default template directory location
    private \$templateDir = __DIR__ . '/../view/';

    // Method to render the template
    public function render(\$template, \$data = [])
    {
        // Construct the full template file path
        \$template_name = \$this->templateDir . DIRECTORY_SEPARATOR . \$template . 'View.php';

        // Check if the template file exists
        if (!file_exists(\$template_name)) {
            // Handle error if the template file doesn't exist
            throw new \Exception("Template file '\$template_name' not found!");
        }
        // echo \$template_name;
        // If data is provided, extract it into variables
        if (!empty(\$data)) {
            extract(\$data);  // This will convert array keys into variables, i.e., \$key => \$value
        }
        // print_r(\$data);

        // Start output buffering to capture template content
        // ob_start();

        // Include the template file
        include(\$template_name);

        // // Get the rendered content and clean the output buffer
        // \$content = ob_get_clean();

        // // Optionally, you could process or manipulate \$content before returning
        // return \$content;
    }
    public function includePartial(\$partial, \$data = []) {}
    public function renderPartial(\$partial, \$data = []) {} // Added to render partials
    public function get(\$template, \$data = []) {} // Added to get rendered view as string
    public function setData(\$data) {} // Added to set data to view
    public function useComponent(\$component) {}
    public function setViewEngine(\$engine_name) {}
    public function dispatchFlashMessage() {}
}
LMN_View,

// WELCOME FILE CONTENTS
"WelcomeModel" => <<< WelcomeModel
<?php

namespace App\Model;

use App\Core\Model;

class WelcomeModel extends Model
{
    public static \$pageTitle = "Welcome to LumenitePHP!";
    public static \$pageHeroText = "Welcome to Lumenite PHP!";
    public static \$pageSubText = "A lightweight and powerful PHP framework for building fast serverless and server-based applications.";
    public static \$exploreText = "Explore the Power of LumenitePHP Framework";
    public static \$logo = [
        'alt' => 'Lumenite Logo',
        'src' => 'assets/images/lumenite.png',
    ];
    public static \$bottomNavLinks = [
        [
            'link_text' => 'Documentation',
            'link_url' => 'https://examples.com//docs',
            'link_icon' => 'fas fa-code mr-2',
        ],
        [
            'link_text' => 'Templates',
            'link_url' => 'https://examples.com/templates',
            'link_icon' => 'fas fa-layer-group mr-2',
        ],
        [
            'link_text' => 'Explore',
            'link_url' => 'https://examples.com/explore',
            'link_icon' => 'fas fa-compass mr-2',
        ],
        [
            'link_text' => 'Forums',
            'link_url' => 'https://examples.com/forums',
            'link_icon' => 'fas fa-comments mr-2',
        ],
        [
            'link_text' => 'Hosting',
            'link_url' => 'https://examples.com/hosting',
            'link_icon' => 'fas fa-server mr-2',
        ],
        [
            'link_text' => 'Examples',
            'link_url' => 'https://examples.com/examples',
            'link_icon' => 'fas fa-book mr-2',
        ]
    ];
}

WelcomeModel,
"WelcomeController" => <<< WelcomeController
<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\WelcomeModel;

require_once __DIR__ . '/../../app/view/components/Welcome.comp.php';
class WelcomeController extends Controller
{
    public function index()
    {
        \$this->view->render('Welcome', \$data = [
            'pageTitle' => WelcomeModel::\$pageTitle,
            'pageSubText' => WelcomeModel::\$pageSubText,
            'pageHeroText' => WelcomeModel::\$pageHeroText,
            'logo' => WelcomeModel::\$logo,
            'exploreText' => WelcomeModel::\$exploreText,
            'bottomNavLinks' => WelcomeModel::\$bottomNavLinks,
        ]);
    }
}

WelcomeController,
"WelcomeView" => <<< WelcomeView
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo \$data['pageTitle']; ?></title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <link
        href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body
    class="bg-white-100 h-screen flex items-center justify-center font-sans p-3">
    <div class="text-center">
        <?php render_logo_image([
            'src' => 'assets/images/lumenite.png',
            'alt' => 'Lumenite Logo',
        ]); ?>

        <h1 class="text-4xl font-bold text-gray-800"><?php echo \$data['pageHeroText']; ?></h1>
        <p class="text-lg text-gray-600 mt-4">
            <?php echo \$data['pageSubText']; ?>
        </p>

        <p class="text-lg text-gray-600 mt-6 underline"><?php echo \$data['exploreText']; ?></p>
        <?php render_nav_link(create_links_comp(
            \$data['bottomNavLinks'],
            'text-gray-800 hover:text-purple-500 border border-gray-300 rounded-lg py-2 px-4 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-purple-500 inline-block'
        ));
        ?>
    </div>
</body>
<script src="assets/js/script.js"></script>

</html>
WelcomeView,
"WelcomeComp" => <<< WelcomeComp
<?php
function create_links_comp(\$data, \$common_comp)
{
    if (empty(\$data)) {
        return "No data found";
        exit(1);
    }

    \$allCompData = "";

    foreach (\$data as \$comp) {
        \$link_text = \$comp['link_text'];
        \$link_url = \$comp['link_url'];
        \$link_icon = \$comp['link_icon'];
        \$link_class = \$common_comp;
        \$allCompData .= <<<LINKCOMP
        <a href="\$link_url" class="\$link_class">
        <i class="\$link_icon"></i> \$link_text
        </a>

        LINKCOMP;
    }

    return \$allCompData;
}

function render_nav_link(\$links)
{
    echo <<<NAVLINKS
     <nav class="space-x-6 text-white mt-6">
        \$links
     </nav>
    NAVLINKS;
    
}


function render_logo_image(\$logo)
{
    echo <<<LOGO
    <img
    src="\$logo[src]"
    alt="\$logo[alt]"
    class="mx-auto mb-2"
    width="150px"
    height="150px" />
    LOGO;
}
WelcomeComp,
"WelcomeDBSchema" => <<< WelcomeDBSchema
WelcomeDBSchema,
"DefaultRoutes" =>  <<< DefaultRoutes
<?php
require_once(__DIR__ . '/../core/Router.php');

use App\Core\Router;

\$router = new Router();

// Frontend Pages
\$router->addRoute('GET', '/', 'Welcome@index');

DefaultRoutes,
"DefaultCSS" =>  <<< DefaultCSS
body {
    background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
}
DefaultCSS,
"DefaultJS" =>  <<< DefaultJS
console.log("It works!!!");
DefaultJS,
"EntryPoint" =>  <<< EntryPoint
<?php
require_once(__DIR__ . '/../app/router/routes.php');
\$method = \$_SERVER['REQUEST_METHOD'];
\$uri = parse_url(\$_SERVER['REQUEST_URI'], PHP_URL_PATH);
\$router->dispatch(\$method, \$uri);
EntryPoint,
"DBPHPCONFIG" =>  <<< DBPHPCONFIG
DBPHPCONFIG,
"APPPHPCONFIG" =>  <<< APPPHPCONFIG
APPPHPCONFIG,
"CONFIGFILEDATA" =>  <<< CONFIGFILEDATA
<?php
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


// composer setup
"ComposerData"=> <<< ComposerData
{
  "autoload": {
    "psr-4": {
      "App\\\": "app/"
    }
  }
}
ComposerData
]);
