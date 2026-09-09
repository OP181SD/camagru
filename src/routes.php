<?php

use App\Controllers\PageController;
use App\Controllers\AuthController;
use App\Controllers\CommentController;
use App\Controllers\PublicationController;
use App\Controllers\LikeController;
use App\Controllers\NotificationController;
use App\Controllers\UpdateController;
use App\Services\TokenService;

$pageController = new PageController();
$authController = new AuthController();
$publicationController = new PublicationController();
$tokenService = new TokenService();
$likeController = new LikeController();
$commentaryController = new CommentController();
$updateController = new UpdateController();
$notificationController = new NotificationController();

$router->addStaticRoute('/', fn() => $pageController->home());

$router->addStaticRoute('/signup', fn() => $pageController->signup());
$router->addStaticRoute('/signup-step-two', fn() => $pageController->signupStepTwo());
$router->addStaticRoute('/signup-success', fn() => $pageController->signupSuccess());
$router->addStaticRoute('/not-found', fn() => $pageController->notFound());


$router->addStaticRoute('/login', fn() => $pageController->login());
$router->addStaticRoute('/forgot-password', fn() => $pageController->forgotPassword());
$router->addStaticRoute('/reset-password', fn() => $pageController->resetPassword());
$router->addStaticRoute('/password-changed', fn() => $pageController->passwordChanged());

$router->addStaticRoute('/verify-email', fn() => $pageController->verifyEmail());
$router->addStaticRoute('/confirm-email', fn() => $tokenService->confirmEmail());

$router->addStaticRoute('/feed', fn() => $pageController->feed());
$router->addStaticRoute('/gallery', fn() => $pageController->gallery());
$router->addStaticRoute('/studio', fn() => $pageController->studio());
$router->addStaticRoute('/settings', fn() => $pageController->settings());


$router->addDynamicRoute('POST', '/register', fn() => $authController->register());
$router->addDynamicRoute('POST', '/register-two', fn() => $authController->registerTwo());
$router->addDynamicRoute('POST', '/cancel-partial-signup', fn() => $authController->CancelPartialSignup());
$router->addDynamicRoute('POST', '/login-auth', fn() => $authController->loginAuth());
$router->addDynamicRoute('POST', '/logout', fn() => $authController->logout());

$router->addDynamicRoute('POST', '/send-mail', fn() => $authController->forgotPasswordSendMail());
$router->addDynamicRoute('POST', '/reset-forgot-password', fn() => $authController->changeForgotPassword());

$router->addDynamicRoute('POST', '/upload-publication-sticker', fn() => $publicationController->createStickerPublication());
$router->addDynamicRoute('POST', '/upload-publication-pictures', fn() => $publicationController->createPicturePublication());
$router->addDynamicRoute('POST', '/delete-publication-posted', fn() => $publicationController->deletePublication());

$router->addDynamicRoute('POST', '/likes-count', fn() => $likeController->LikesCount());
$router->addDynamicRoute('GET', '/likes-status', fn() => $likeController->likesStatus());

$router->addDynamicRoute('POST', '/commentary-published', fn() => $commentaryController->insertCommentary());

$router->addDynamicRoute('POST', '/settings/update', fn() => $updateController->settingsUpdate());

$router->addDynamicRoute('POST', '/update-comment-notif', fn() => $notificationController->updateCommentNotification());
