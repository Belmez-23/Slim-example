<?php

// Подключение автозагрузки через composer
//!команда запуска - make term , a не php -S localhost:8080!
require_once  __DIR__ . "/../src/CourseRepository.php";
require_once  __DIR__ . "/../src/UserRepository.php";
require_once  __DIR__ . "/../src/Validator.php";
//уточнить как ссылаться на пространство имён, не файл в репозитории
require __DIR__ . '/../vendor/autoload.php';

// Контейнеры в этом курсе не рассматриваются (это тема связанная с самим ООП), но если вам интересно, то посмотрите DI Container

use Slim\Factory\AppFactory;
use DI\Container;

$container = new Container();
$container->set('renderer', function () {
    // Параметром передается базовая директория, в которой будут храниться шаблоны
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

//$app = AppFactory::create();
//$app->addErrorMiddleware(true, true, true);

// Обработчик
$app->get('/', function ($request, $response) {
    $response->getBody()->write('<h1>Hello</h1>
<a href="/users">Страница пользователей</a>
<br><a href="/courses">Страница курсов</a>');
    return $response;
});
/************** ПОЛЬЗОВАТЕЛИ **************/
$repo_user = new App\UserRepository();

$app->get('/users', function ($request, $response) use ($repo_user) { //+
    $term = $request->getQueryParam('term');
    $users = $repo_user->search($term);
    $params = ['users' => $users];
    return $this->get('renderer')->render($response, 'users/index.phtml', $params);
});

//$repo = new UserRepository();

$app->get('/users/new', function ($request, $response) { //+
    $params = [
        'user' => ['name' => '', 'email' => ''],
        'errors' => []
    ];
    return $this->get('renderer')->render($response, "users/new.phtml", $params);
});

$app->post('/users', function ($request, $response) use ($repo_user) { //+
    $user = $request->getParsedBodyParam('user');
    $validator = new App\Validator(); //проверка правильности ввода
    $errors = $validator->validate($user);
    if (count($errors) === 0) {
        $repo_user->save($user); //записать юзера
        return $response->withRedirect('/users', 302);
    }
    $params = [
        'user' => $user,
        'errors' => $errors
    ];
    return $this->get('renderer')->render($response, "users/new.phtml", $params);
});

$app->get('/users/{id}', function ($request, $response, $args) { //старый код
    $params = ['id' => $args['id'], 'nickname' => 'user-' . $args['id']];
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
});

$app->post('/courses', function ($request, $response) use ($repo_course) { //+
    $course = $request->getParsedBodyParam('course');
    $validator = new App\Validator(); //проверка правильности ввода
    $errors = $validator->validate($course);
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

$app->get('/courses', function ($request, $response) use ($repo_course){ //-
    $term = $request->getQueryParam('term');
    $courses = $repo_course->search($term) ; //filter here
    $params = ['courses' => $courses];
    return $this->get('renderer')->render($response, "courses/index.phtml", $params);
});

$app->get('/courses/{id}', function ($request, $response, array $args) { //старый код
    $id = $args['id'];
    return $response->write("Course id: {$id}");
});

$app->run();