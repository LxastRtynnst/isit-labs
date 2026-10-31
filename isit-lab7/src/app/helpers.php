<?php
declare(strict_types=1);

function config(string $key): mixed
{
    static $config = null;
    $config ??= require BASE_PATH . '/config/app.php';
    return $config[$key] ?? null;
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function view(string $template, array $data = []): void
{
    extract($data);
    require APP_PATH . '/Views/layout/header.php';
    require APP_PATH . '/Views/' . $template . '.php';
    require APP_PATH . '/Views/layout/footer.php';
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function notFound(): never
{
    http_response_code(404);
    view('errors/404', ['path' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)]);
    exit;
}

// --- НОВЫЕ ФУНКЦИИ ДЛЯ API ---

function json_response(mixed $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function json_error(string $message, int $status, array $errors = []): never
{
    $body = ['error' => $message];
    if ($errors) {
        $body['errors'] = $errors;
    }
    json_response($body, $status);
}

function no_content(): never
{
    http_response_code(204);
    exit;
}

function json_input(): array
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        json_error('Тело запроса должно быть корректным JSON-объектом', 400);
    }
    return $data;
}