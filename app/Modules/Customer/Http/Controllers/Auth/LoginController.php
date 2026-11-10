<?php

namespace App\Modules\Customer\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Customer\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 422);
        }

        if ($user->role !== 'customer') {
            return response()->json(['message' => 'API ini khusus akun customer.'], 403);
        }

        if ($user->status !== 'active') {
            return response()->json(['message' => 'Akun tidak aktif.'], 403);
        }

        return response()->json([
            'token' => $user->createToken('customer')->plainTextToken,
            'user' => $user->only('id', 'name', 'email', 'role'),
        ]);
    }
}
