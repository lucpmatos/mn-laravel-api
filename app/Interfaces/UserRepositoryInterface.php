<?php

namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getAll(int $perPage = 0, array $params = []);
    public function getById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
