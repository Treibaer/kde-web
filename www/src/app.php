<?php

require __DIR__ . '/../../vendor/autoload.php';
\Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace('JMS\Serializer\Annotation',
    __DIR__ . "/vendor/jms/serializer/src");
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

putenv('ROOT=' . __DIR__ . DIRECTORY_SEPARATOR . '../');


$apiManager = new \KDE\Manager();
$coreManager = $apiManager->core();

$user = $apiManager->worker()->login();

$env = new TBCore\Worker\Environment();

$headerView = new stdClass();


function f($v)
{
    return number_format($v / 100, 2, '.', '');
}


// controller handling
$requestURI = $_SERVER['REQUEST_URI'];

// defaults
$nameSpacePrefix = 'KDE';
$pathPrefix = 'App';

// TODO: prefix handling
if (str_starts_with($requestURI, '/api')) {
    $requestURI = '/' . substr($requestURI, 4);
    $nameSpacePrefix = 'KDEApi';
    $pathPrefix = 'Api';
}

$isAdmin = str_starts_with($requestURI, '/admin');


$controller = null;
$page = '/';


$requestURI = explode('?', strtolower($requestURI))[0];
if (str_ends_with($requestURI, '/')) {
    $requestURI = substr($requestURI, 0, -1);
}
$requestURI = join('/', array_map("ucfirst", explode('/', $requestURI)));

$pages = ["Category", "Profile", "Session", "User"];
foreach ($pages as $page) {
    if (str_starts_with($requestURI, '/' . $page . '/')) {
        // show category controller
        $requestURI = "/" . $page;
        break;
    }
}
// search in app
list($page, $controller) = determinePageController($requestURI, $nameSpacePrefix, $pathPrefix);
if (null === $page) {
    // search in core
    $pathPrefix = '../Core';
    list($page, $controller) = determinePageController($requestURI, 'TBCore', $pathPrefix);
}

/**
 * @param string $requestURI
 * @param string $nameSpacePrefix
 * @param string $pathPrefix
 * @return array
 */
function determinePageController($requestURI, $nameSpacePrefix = 'TBCore', $pathPrefix = 'Core'): array
{
    $page = null;
    $controller = null;
    // /account -> AccountController
    // /account -> Account/IndexController
    // /account/detail -> Account/DetailController
    // /account/detail -> Account/Detail/IndexController
    $file = __DIR__ . DIRECTORY_SEPARATOR . $pathPrefix . '/Controller' . ucfirst($requestURI) . 'Controller';
    if (file_exists($file . '.php')) {
        $page = '/' . ucfirst(substr(strtolower($requestURI), 1));
        $controller = $nameSpacePrefix . '/Controller' . ucfirst($requestURI) . 'Controller';
    } else {
        $file = __DIR__ . DIRECTORY_SEPARATOR . $pathPrefix . '/Controller' . $requestURI . '/IndexController';
        if (file_exists($file . '.php')) {
            $page = $requestURI . '/index';
            $controller = $nameSpacePrefix . '/Controller' . $requestURI . '/IndexController';
        }
    }
    return [$page, $controller];
}


$publicApis = ['//kde/character/get'];
if ($nameSpacePrefix != "TBApi" || !in_array($page, $publicApis)) {
// handle login
    if (null === $user && !str_contains(strtolower($page), '/login')) {
        if (isset($_REQUEST['sessionId'])) {
            echo json_encode(['success' => false, 'logout' => true]);
            die;
        }
        $ru = $_SERVER['REQUEST_URI'] ?? "";
        header('Location: /login?ref=' . $ru);
    }
}

$controller = str_replace('//', '/', $controller);
if (null == $controller) {
    // throw 404 page
    $controller = TBCore\Controller\PageNotFoundController::class;
    $page = 'pageNotFound';
    if ('TBApi' === $nameSpacePrefix) {
        header("HTTP/1.0 404 Not Found");
        echo json_encode(["success" => false, "error" => "page not found"]);
        die;
    }
}

$controller = str_replace('/', '\\', $controller);
$view = new stdClass();
// global vars
$view->currentYear = date('Y');
/** @var TBCore\Controller\IndexController $controller */
$isCoreController = str_contains($controller, 'TBCore\Controller');
$controller = new $controller($view, $_REQUEST, $isCoreController ? $apiManager->core() : $apiManager);
$controller->decode();
$controller->prepare();
$controller->start();
$renderFiles = $controller->render();
if ('Api' !== $pathPrefix) {
    // ready to render
    $twig = createTwig($pathPrefix);
    // add twig functions
    $controller->twigFunctions($twig);
    if (null === $renderFiles) {
        $split = explode('/', $page);
        if (count($split) > 0) {
            $split[count($split) - 1] = strtolower($split[count($split) - 1]);
        }
        $page = join('/', $split);
        $renderFiles = [$page . '.html.twig'];
    }
    $header = "header";
    $footer = "footer";
    if ($isAdmin) {
        $header = "Admin/header";
        $footer = "Admin/footer";
    }
    if (!in_array(strtolower($page), ['/login', '/logout', 'pagenotfound'])) {
        echo $twig->render($header . '.html.twig', (array)$view);
    }
    foreach ($renderFiles as $renderFile) {
        echo $twig->render($renderFile, (array)$view);
    }
    if (!in_array(strtolower($page), ['/login', '/logout', 'pagenotfound'])) {
        echo $twig->render($footer . '.html.twig', (array)$view);
    }
}

$apiManager->database()->dbDummy()->close();

function createTwig($pathPrefix): Twig_Environment
{
    // try catch, so the config.php could be included outside this folder, i.e. e.g. from api/*.php
    $loader = new Twig_Loader_Filesystem(__DIR__ . '/' . $pathPrefix . '/View');
    $twig = new Twig_Environment($loader, []);
    $twig->addExtension(new Twig_Extension_Debug());

    $twig->addFunction(new \Twig_SimpleFunction('f', function ($value, $n = false, $n2 = false) {
        if ($n2) {
            return f(ceil($value));
        }
        if ($n) {
            return f(ceil($value)) . '€';
        }
        return f(ceil($value)) . '€';
    }));
    $twig->addFunction(new \Twig_SimpleFunction('d', function ($value) {
        return date('d.m.Y', $value);
    }));
    $twig->addFunction(new \Twig_SimpleFunction('dd', function ($value) {
        return date('d.m.Y H:i:s', $value);
    }));
    $twig->addFunction(new \Twig_SimpleFunction('formatSize', function ($value) {
        $s = ['B', 'K', 'M', 'G'];
        foreach ($s as $i => $v) {
            if ($value < pow(1024, ($i + 1))) {
                $rounded = $value / pow(1024, $i);
                $numberOfDigitsBeforeComma = ceil(log10((int)$rounded));
                $additionalDigits = $numberOfDigitsBeforeComma < 3 ? (2 - $numberOfDigitsBeforeComma) : 0;
                return round($rounded, $additionalDigits) . $v;
            }
        }
        if ($value < 1024) {
            return $value . 'B';
        } else if ($value < 1024 * 1024) {
            return round($value / 1024) . 'K';
        }
        return "0";
    }));
    return $twig;
}
