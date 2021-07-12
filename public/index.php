<?php

// Подключение автозагрузки через composer
//!команда запуска - make start , a не php -S localhost:8080!

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// Начало сессии в PHP
session_start();

$container = new Container();
$container->set('renderer', function () {
    // Параметром передается базовая директория, в которой будут храниться шаблоны
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
$container->set('flash', function () {
    return new \Slim\Flash\Messages();
});

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

//$app = AppFactory::create();
//$app->addErrorMiddleware(true, true, true);
$router = $app->getRouteCollector()->getRouteParser();

// ************* ОБРАБОТЧИК ************* //
// ************* ГЛАВНАЯ ************* //
$app->get('/', function ($request, $response) use ($router) { //+
    //var_dump($_SESSION);
    $router->urlFor('users');
    $router->urlFor('user'); //users/new
    $router->urlFor('courses');
    $router->urlFor('course'); //courses/new

    return $this->get('renderer')->render($response, '/../index.php');
});

/************** ПОЛЬЗОВАТЕЛИ **************/
$repo_user = new App\UserRepository();

$app->get('/users', function ($request, $response) use ($repo_user) { //+
    $term = $request->getQueryParam('term');
    $users = $repo_user->search($term);
    $messages = $this->get('flash')->getMessages();
    echo $messages['user-status'][0];
    $params = ['users' => $users];
    return $this->get('renderer')->render($response, 'users/index.phtml', $params);
})->setName('users');

$app->get('/users/new', function ($request, $response) { //+
    $params = [
        'user' => ['name' => '', 'email' => ''],
        'errors' => []
    ];
    return $this->get('renderer')->render($response, "users/new.phtml", $params);
})->setName('user');

$app->post('/users', function ($request, $response) use ($repo_user) { //+
    $user = $request->getParsedBodyParam('user');
    //$validator = new \App\Validator(); //проверка правильности ввода
    $errors = $repo_user->validate($user);
    if (count($errors) === 0) {
        $repo_user->save($user); //записать юзера
        $this->get('flash')->addMessage('user-status', 'Пользователь создан');
        return $response->withRedirect('/users', 302);
    }
    $params = [
        'user' => $user,
        'errors' => $errors
    ];
    $this->get('flash')->addMessage('user-status', 'Пользователь не создан');
    return $this->get('renderer')->render($response, "users/new.phtml", $params);
});

$app->get('/users/{id}', function ($request, Response $response, $args) use ($repo_user) { //adapted
    $user = $repo_user->find($args['id']);
    if(!($user)){
        $response->getBody()->write('Пользователь не найден');
        //выводит ошибку, но не устанавливает статус?
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'text/html');
    }
    //var_dump($user);
    $params = ['id' => $user['id'], 'name' => $user['name']];
    return $this->get('renderer')->render($response, 'users/show.phtml', $params);
});

/************** КУРСЫ **************/
$repo_course = new App\CourseRepository();

$app->get('/courses/new', function ($request, $response) { //+
    $params = [
        'course' => ['title' => '', 'paid' => ''],
        'errors' => []
    ];
    return $this->get('renderer')->render($response, "courses/new.phtml", $params);
})->setName('course');

$app->post('/courses', function ($request, $response) use ($repo_course) { //+
    $course = $request->getParsedBodyParam('course');
    //$validator = new App\Validator(); //проверка правильности ввода
    $errors = $repo_course->validate($course);
    if (count($errors) === 0) {
        $repo_course->save($course); //записать
        return $response->withRedirect('/courses', 302);
    }
    $params = [
        'course' => $course,
        'errors' => $errors
    ];
    return $this->get('renderer')->render($response, "courses/new.phtml", $params);
});

$app->get('/courses', function ($request, $response) use ($repo_course){ //+
    $term = $request->getQueryParam('term');
    $courses = $repo_course->search($term) ; //filter here
    $params = ['courses' => $courses];
    return $this->get('renderer')->render($response, "courses/index.phtml", $params);
})->setName('courses');

$app->get('/courses/{id}', function ($request, $response, array $args) { //старый код
    $id = $args['id'];
    return $response->write("Course id: {$id}");
});

//**************X**************//
$app->get('/foo', function ($req, $res) {
    // Добавление флеш-сообщения. Оно станет доступным на следующий HTTP-запрос.
    // 'success' — тип флеш-сообщения. Используется при выводе для форматирования.
    // Например можно ввести тип success и отражать его зелёным цветом (на Хекслете такого много)
    $this->get('flash')->addMessage('success', 'This is a message');
    $this->get('flash')->addMessage('error', 'This is a second message');
    return $res->withRedirect('/bar');
});

$app->get('/bar', function ($req, $res, $args) {
    // Извлечение flash сообщений установленных на предыдущем запросе
    $messages = $this->get('flash')->getMessages();
    print_r($messages); // => ['success' => ['This is a message']]

    $params = ['flash' => $messages];
    return $this->get('renderer')->render($res, 'users/show.phtml', $params);
});

$app->run();