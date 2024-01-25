<?php

namespace App\Http\Controllers\Api;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // Set validation
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
            ], 422);
        }

        // Dapatkan credentials dari request
        $credentials = $request->only('email', 'password');

        // Jika auth gagal
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        $user = auth()->user();

        // Redirect berdasarkan peran setelah autentikasi berhasil
        return $this->authenticated($request, $user, $token);
    }

    /**
     * Handle the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $user
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user, $token)
{
   // Lakukan otentikasi pengguna
auth()->login($user);

$response = [
    'success' => true,
    'user' => $user,
    'token' => $token,
    'roles' => $user->getRoleNames(), // Use getRoleNames on the user instance
    'permissions' => $user->getPermissionNames(), // Mendapatkan nama-nama permission

    // Periksa dan tambahkan roles ke array jika pengguna memiliki peran tertentu
];

 // Periksa role user sebelum memberikan permissions tertentu
 if ($user->hasRole('admin')) {
    $response['permissions'] = [
        'roles.index',
        'roles.update',
        'roles.delete',
        // Tambahkan permissions lainnya sesuai kebutuhan
    ];
} else {
    // Jika user bukan admin, set permissions menjadi array kosong atau sesuai kebutuhan
    $response['permissions'] = [];
}
return response()->json($response, 200);
}
}
