<?php 
require_once __DIR__.'./../src/Config.php';
require_once __DIR__. './../vendor/autoload.php';

use App\SQLiteConnection;
use App\Model\IndexModel;
use App\Controller\IndexController;
use App\View\IndexView;

date_default_timezone_set(UTC_TIME_ZONE);


$pdo = (new SQLiteConnection())->connect();
if ($pdo != null)
    echo 'Connected to the SQLite database successfully!';
else
    echo 'Whoops, could not connect to the SQLite database!';

$url = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'],'/')) : '/';

if ($url == '/')
{
    $indexModel = new IndexModel();
    $indexController = new IndexController($indexModel);
    $indexView = new IndexView($indexController, $indexModel);

    print $indexView->index();

}else{

    //The first element should be a controller
    $requestedController = ucfirst($url[0]) ; 

    // If a second part is added in the URI, 
    // it should be a method
    $requestedAction = isset($url[1])? $url[1] :'';

    // The remain parts are considered as 
    // arguments of the method
    $requestedParams = array_slice($url, 2); 

    $ctrlPath=PROJECT_DIR.'Controllers/'.$requestedController.'Controller.php';
    if (file_exists($ctrlPath))
     {


        $modelName      = $requestedController.'Model';
        $controllerName = $requestedController.'Controller';
        $viewName       = $requestedController.'View';

        $controllerObj  = new $controllerName( new $modelName );
        $viewObj        = new $viewName( $controllerObj, new $modelName );


        // If there is a method - Second parameter
        if ($requestedAction != '')
        {
            // then we call the method via the view
            // dynamic call of the view
            print $viewObj->$requestedAction($requestedParams);

        }

     }else{

         header('HTTP/1.1 404 Not Found');
         die('404 - Not found');
    }
}