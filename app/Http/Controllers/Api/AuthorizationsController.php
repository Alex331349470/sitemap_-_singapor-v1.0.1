<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationRequest;
use Illuminate\Http\Request;
use Auth;

class AuthorizationsController extends Controller
{
    //用户登录
    public function store(AuthorizationRequest $request)
    {
        $credentials['email'] = $request->email;
        $credentials['password'] = $request->password;

        if (!Auth::attempt($credentials)) {
            return $this->response->errorUnauthorized('用户邮箱或者密码错误！');
        }

        $token = Auth::guard('api')->fromUser(Auth::user());

        return $this->respondWithToken($token)->setStatusCode(201);
    }

    /*
   * 刷新token
   */
    public function update()
    {
        $token = Auth::guard('api')->refresh();
        return $this->respondWithToken($token);
    }

    /*
    * 删除token
    */
    public function destroy()
    {
        Auth::guard('api')->logout();
        return $this->response->noContent();
    }

    /*
   * 返回token值，类型，以及过期时间的函数
   */
    protected function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }
}
