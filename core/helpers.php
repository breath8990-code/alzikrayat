<?php
function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'); }
function basePath(): string
{
    static $base = null;
    if ($base === null) {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/');
        $base = rtrim(dirname($scriptName), '/.');
        if ($base === '/') $base = '';
    }
    return $base;
}
function url(string $path = ''): string { return basePath().'/'.ltrim($path, '/'); }
function redirect(string $path): never { header('Location: '.url($path)); exit; }
function isLoggedIn(): bool { return isset($_SESSION['user']); }
function currentUser(): ?array { return $_SESSION['user'] ?? null; }
function requireLogin(): void { if (!isLoggedIn()) { flash('error', 'Please login to continue.'); redirect('login'); } }
function flash(string $type, string $message): void { $_SESSION['flash'][$type] = $message; }
function old(string $key): string { return e($_POST[$key] ?? ''); }
function csrfToken(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function verifyCsrf(): void { if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('Invalid CSRF token'); } }
function lastLogin(): ?string { return $_COOKIE['last_login'] ?? null; }
?>
