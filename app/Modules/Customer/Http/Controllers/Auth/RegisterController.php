<?php

namespace App\Modules\Customer\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Customer\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // di-hash otomatis oleh cast 'hashed'
            'role' => 'customer',            // dipaksa, tidak dari input
            'status' => 'active',
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();

        return response()->json([
            'token' => $user->createToken('customer')->plainTextToken,
            'user' => $user->only('id', 'name', 'email', 'role'),
        ], 201);
    }
}
