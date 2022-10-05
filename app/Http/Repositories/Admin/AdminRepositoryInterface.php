<?php

namespace App\Http\Repositories\Admin;

use Illuminate\Http\Request;

interface AdminRepositoryInterface {
    public function createAdminUser(array $data);
}