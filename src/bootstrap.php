<?php

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Application.php';

require_once __DIR__ . '/Controllers/PageController.php';
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Controllers/PublicationController.php';
require_once __DIR__ . '/Controllers/LikeController.php';
require_once __DIR__ . '/Controllers/CommentController.php';
require_once __DIR__ . '/Controllers/NotificationController.php';
require_once __DIR__ . '/Controllers/UpdateController.php';

require_once __DIR__ . '/helpers/View.php';
require_once __DIR__ . '/helpers/Validator.php';
require_once __DIR__ . '/helpers/database.php';
require_once __DIR__ . '/helpers/date.php';

require_once __DIR__ . '/models/Token.php';
require_once __DIR__ . '/models/Auth/Register.php';
require_once __DIR__ . '/models/Auth/Login.php';
require_once __DIR__ . '/models/Interaction/Like.php';
require_once __DIR__ . '/models/Interaction/Comment.php';
require_once __DIR__ . '/models/Interaction/Notification.php';
require_once __DIR__ . '/models/Interaction/Update.php';
require_once __DIR__ . '/models/Publication.php';

require_once __DIR__ . '/Exceptions/JsonException.php';

require_once __DIR__ . '/Services/AuthService.php';
require_once __DIR__ . '/Services/MailService.php';
require_once __DIR__ . '/Services/RegisterService.php';
require_once __DIR__ . '/Services/LoginService.php';
require_once __DIR__ . '/Services/TokenService.php';
require_once __DIR__ . '/Services/LikeService.php';
require_once __DIR__ . '/Services/CommentService.php';
require_once __DIR__ . '/Services/NotificationService.php';
require_once __DIR__ . '/Services/PublicationService.php';
require_once __DIR__ . '/Services/UpdateService.php';

use App\Core\Router;
use App\Core\Application;
use App\Helpers\View;

use App\Services\AuthService;

$authService = AuthService::getInstance();
$authService->launchSession();

$router  = Router::getInstance();
$view    = View::getInstance(__DIR__ . '/views');
$view->setLayout('main');

require_once __DIR__ . '/routes.php';

$app = Application::getInstance();
$app->setRouter($router);
$app->run();
