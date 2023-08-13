<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getAuthenticatedUser()
    {
        $user_id = Auth::id();
        $user = User::find($user_id);
        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions();

        return response()->json([
            'id' => $user_id,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'user_name' => $user->user_name,
                'email' => $user->email,
            ],
            'roles' => $roles,
            'permissions' => $permissions->pluck('name'),
        ]);
    }
}
