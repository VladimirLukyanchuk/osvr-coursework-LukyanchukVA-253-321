<?php
use App\Controllers\{ArticlesController, AuthController, CommentsController, FeedbackController, PlanController};

$router->add('GET', '/', [ArticlesController::class, 'home']);
$router->add('GET', '/materials', [ArticlesController::class, 'index']);
$router->add('GET', '/articles/{id}', [ArticlesController::class, 'show']);
$router->add('GET', '/plan', [PlanController::class, 'index']);
$router->add('POST', '/plan', [PlanController::class, 'index']);
$router->add('GET', '/feedback', [FeedbackController::class, 'index']);
$router->add('POST', '/feedback', [FeedbackController::class, 'index']);
$router->add('GET', '/login', [AuthController::class, 'login']);
$router->add('POST', '/login', [AuthController::class, 'signIn']);
$router->add('GET', '/register', [AuthController::class, 'register']);
$router->add('POST', '/register', [AuthController::class, 'signUp']);
$router->add('POST', '/logout', [AuthController::class, 'logout']);
$router->add('GET', '/cabinet', [ArticlesController::class, 'cabinet']);
$router->add('GET', '/cabinet/create', [ArticlesController::class, 'create']);
$router->add('POST', '/cabinet/create', [ArticlesController::class, 'store']);
$router->add('GET', '/cabinet/{id}/edit', [ArticlesController::class, 'edit']);
$router->add('POST', '/cabinet/{id}/edit', [ArticlesController::class, 'update']);
$router->add('POST', '/cabinet/{id}/delete', [ArticlesController::class, 'destroy']);
$router->add('POST', '/articles/{id}/comments', [CommentsController::class, 'store']);
$router->add('GET', '/comments/{id}/edit', [CommentsController::class, 'edit']);
$router->add('POST', '/comments/{id}/edit', [CommentsController::class, 'update']);
$router->add('POST', '/comments/{id}/delete', [CommentsController::class, 'destroy']);
