<?php
// app/Controllers/Api/UserApiController.php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\UserRepository;

final class UserApiController
{
    private UserRepository $users;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    public function index(): void
    {
        json_response($this->users->all());
    }

    public function show(string $id): void
    {
        $user = $this->users->find((int)$id);
        if ($user === null) {
            json_error('Пользователь не найден', 404);
        }
        json_response($user);
    }

    public function store(): void
    {
        $data = json_input();
        $errors = $this->validate($data);
        if ($errors) {
            json_error('Ошибка проверки данных', 422, $errors);
        }
        $user = $this->users->add($this->clean($data));
        header('Location: /api/users/' . $user['id']);
        json_response($user, 201);
    }

    public function update(string $id): void
    {
        $user = $this->users->find((int)$id);
        if ($user === null) {
            json_error('Пользователь не найден', 404);
        }
        $data = json_input();
        $merged = array_merge($user, $this->clean($data));
        $errors = $this->validate($merged);
        if ($errors) {
            json_error('Ошибка проверки данных', 422, $errors);
        }
        $this->users->update((int)$id, $merged);
        json_response($merged);
    }

    public function destroy(string $id): void
    {
        $user = $this->users->find((int)$id);
        if ($user === null) {
            json_error('Пользователь не найден', 404);
        }
        $this->users->delete((int)$id);
        no_content();
    }

    private function validate(array $data): array
    {
        $errors = [];
        $name = trim($data['name'] ?? '');
        if (mb_strlen($name) < 2) {
            $errors['name'] = 'Имя — не короче 2 символов';
        }
        $email = trim($data['email'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Некорректный адрес электронной почты';
        }
        $role = $data['role'] ?? '';
        if (!in_array($role, ['manager', 'user'], true)) {
            $errors['role'] = 'Роль: manager или user';
        }
        return $errors;
    }

    private function clean(array $data): array
    {
        return array_intersect_key($data, array_flip(['name', 'email', 'role']));
    }
}