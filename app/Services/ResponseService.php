<?php

namespace App\Services;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Lang;

class ResponseService implements Responsable
{
    public int $status = 200;
    public string $message = 'OK';
    public mixed $data;
    public array $errors = [];

    public function status(int $status): ResponseService
    {
        $this->status = $status;
        return $this;
    }

    public function message(string $message): ResponseService
    {
        $this->message = (Lang::has($message) ? __($message) : $message);
        return $this;
    }

    public function data($data): ResponseService
    {
        $this->data = $data;
        return $this;
    }

    public function errors($errors): ResponseService
    {
        $this->errors = (array)$errors;
        return $this;
    }

    public function toResponse($request)
    {
        return response([
            'message' => $this->message,
            'errors' => $this->errors,
            'data' => $this->data,
        ], $this->status);
    }
}
