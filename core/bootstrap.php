<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Photo.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/PhotoController.php';
require_once __DIR__ . '/../controllers/CommentController.php';

function render(string $view, array $data = []): void
{
    extract($data);
    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/' . $view . '.php';
    require __DIR__ . '/../views/layouts/footer.php';
}
?>
