<?php
require_once __DIR__.'/../core/bootstrap.php';
function homePage(): void
{
    $photoModel = new Photo();
    $db = Database::connect();
    render('home', ['title'=>'Home', 'photoCount'=>$photoModel->count(), 'userCount'=>(int)$db->query('SELECT COUNT(*) FROM users')->fetchColumn()]);
}
$router = new Router();
$router->add('GET','/','homePage');
$router->add('GET','/photos',[new PhotoController(),'index']);
$router->add('GET','/photo/(\\d+)',[new PhotoController(),'show']);
$router->add('GET','/upload',[new PhotoController(),'create']);
$router->add('POST','/upload',[new PhotoController(),'store']);
$router->add('POST','/photo/(\\d+)/delete',[new PhotoController(),'delete']);
$router->add('POST','/photo/(\\d+)/comments',[new CommentController(),'store']);
$router->add('GET','/login',[new AuthController(),'showLogin']);
$router->add('POST','/login',[new AuthController(),'login']);
$router->add('GET','/register',[new AuthController(),'showRegister']);
$router->add('POST','/register',[new AuthController(),'register']);
$router->add('GET','/logout',[new AuthController(),'logout']);
$router->add('GET','/about',fn()=>render('about/index',['title'=>'About Us']));
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.');
if ($base !== '' && $base !== '/' && str_starts_with($requestPath, $base)) {
    $requestPath = substr($requestPath, strlen($base)) ?: '/';
}
$router->dispatch($_SERVER['REQUEST_METHOD'], $requestPath);
?>
