<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
/*$container['renderer'] = function ($c) {
$settings = $c->get('settings')['renderer'];
return new Slim\Views\PhpRenderer($settings['template_path']);
};*/

// Register component on container
/*$container['view'] = function ($c) {

    switch ($c['settings']['environment']) {

        case 'development':
            $view = new \Slim\Views\Twig('../templates/', [
                'cache' => false,
                'debug' => true,
            ]);
            break;

        default:
            $view = new \Slim\Views\Twig('../templates/', [
                'cache' => '../cache',
                'debug' => false,
            ]);
            break;

    }

    // Instantiate and add Slim specific extension
    $router = $c->get('router');
    $uri    = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};*/

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger   = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

/**
 * @brief Database connection PDO
 * @details [long description]
 *
 * @param E [description]
 * @param N [description]
 *
 * @return [description]
 */
$container['db'] = function ($c) {
    $db  = $c['settings']['db'];
    $pdo = new PDO('sqlite:' . $db['database']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    return $pdo;
};

/*
$app->add(new Tuupola\Middleware\HttpBasicAuthentication([
    "path"    => "/admin",
    "secure"  => true,
    "relaxed" => ["localhost", "192.168.0.52"],
    "users"   => [
        "john"  => "sturgeon",
        "karen" => "pippin161071",
    ],
]));
*/

//application models
$container['headlineModelBase'] = function ($c) {
    return new Headline\Model\Base($c);
};
$container['headlineModelItem'] = function ($c) {
    return new Headline\Model\Item($c);
};

$container['headlineModelContent'] = function ($c) {
    return new Headline\Model\Content($c);
};
$container['headlineModelTag'] = function ($c) {
    return new Headline\Model\Tag($c);
};
$container['headlineModelType'] = function ($c) {
    return new Headline\Model\Type($c);
};

//application services
$container['headlineServiceBase'] = function ($c) {
    return new Headline\Service\Base($c);
};

$container['headlineServiceAggregate'] = function ($c) {
    return new Headline\Service\Aggregate($c);
};

$container['headlineServiceApi'] = function ($c) {
    return new Headline\Service\Api($c);
};



//source services
$container['headlineServiceSourceGapi'] = function ($c) {
    return new Headline\Service\Source\Gapi($c);
};
$container['headlineServiceSourceNewsriver'] = function ($c) {
    return new Headline\Service\Source\Newsriver($c);
};
