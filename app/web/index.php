<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/app.db',
    ),
));

$app->before(function (Request $request) use ($app){
    $sql = "CREATE TABLE IF NOT EXISTS answers (id INTEGER PRIMARY KEY AUTOINCREMENT, user VARCHAR(255), question INT, answer INT)";
    $post = $app['db']->executeUpdate($sql);

    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->post('/answers', function (Request $request) use ($app) {
    $user = $request->request->get('user');
    $question = $request->request->get('question');
    $answer = $request->request->get('answer');

    $sql = "INSERT INTO answers (user, question, answer) values (?,?,?)";
    $app['db']->executeUpdate($sql, [$user, $question, $answer]);

    return new JsonResponse(['id' => (int) $app['db']->lastInsertId()], 201);
});

$app->get('/answers', function () use ($app) {
    $sql = "select * from answers";
    $answers = $app['db']->fetchAll($sql);

    foreach ($answers as &$answer) {
        $answer['question'] = [
            'id' => (int) $answer['question'],
            'links' => [
                'href' => sprintf('http://private-28a8e2-pools13.apiary-mock.com/questions/%s', $answer['question']),
                'rel' => 'self',
                'type' => 'GET'
            ],
        ];
    }

    return $app->json($answers);
});

$app->run();
