<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);    
require_once 'TaskModel.php';
require_once 'TaskView.php';
require_once 'TaskController.php';

$view = new TaskView();
$controller = new TaskController($view);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index';

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'search':
        $controller->search();
        break;
    case 'refresh':
        $controller->refresh();
        break;
    case 'upload-image':
        $controller->uploadImage();
        break;
    default:
        http_response_code(404);
        echo "Page not found";
}


