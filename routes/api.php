<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1.0.1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array', 'bindings', 'cors'],
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        //登录
        $api->post('authorizations', 'AuthorizationsController@store')
            ->name('api.authorization.store');
        //token刷新
        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('api.authorization.update');
        //token销毁
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('api.authorizations.destroy');
    });

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group([
            'middleware' => 'api.auth'
        ], function ($api) {
            //api版本
            $api->get('version', function () {
                return response('This is version 1.0.1');
            });

            //公司名称搜索
            $api->post('search', 'ElasticsearchController@search')
                ->name('api.search');

            //公司id显示
            $api->get('search/{id}', 'ElasticsearchController@show')
                ->name('api.search.show');

            //数据上传
            $api->post('csv', 'ElasticsearchController@csv')
                ->name('api.csv');

            //sitemap索引信息
            $api->get('indices', 'ElasticsearchController@indices')
                ->name('api.indices');

        });
    });

});
