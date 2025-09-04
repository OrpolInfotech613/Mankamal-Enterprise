<?php

namespace App\Traits;

use App\Models\Role;

trait BranchAuthTrait
{
    protected function authenticateAndConfigureBranch()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        if ($user->is_active == '0') {
            return response()->json([
                'success' => false,
                'message' => 'User account is not active'
            ], 403);
        }

        $role = Role::where('id', $user->role_id)->first();


        // configureBranchConnection($branch);
        // }

        return [
            'user' => $user,
            'role' => $role
        ];
    }
}
