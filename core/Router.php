<?php
class Router
{
    private array $routes = [];
    public function add(string $method, string $pattern, callable $handler): void { $this->routes[] = [$method, '#^'.rtrim($pattern, '/').'/?$#', $handler]; }
    public function dispatch(string $method, string $path): void
    {
        $path = parse_url($path, PHP_URL_PATH) ?: '/';
        foreach ($this->routes as [$routeMethod, $regex, $handler]) {
            if ($method !== $routeMethod) continue;
            if (preg_match($regex, $path, $matches)) { array_shift($matches); call_user_func_array($handler, $matches); return; }
        }
        http_response_code(404); render('photos/not-found', ['title' => 'Page Not Found']);
    }
}
?>
