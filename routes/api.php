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

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array', 'bindings']
], function($api) {
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function($api){
        // 短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');

        // 用户注册
        $api->post('users', 'UsersController@store')
            ->name('api.users.store');

        // 图片验证码
        $api->post('captchas', 'CaptchasController@store')
            ->name('api.captchas.store');

        $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->name('api.socials.authorizations.store');

        $api->post('authorizations', 'AuthorizationsController@store')
            ->name('api.authorizations.store');

        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('api.authorizations.update');

        $api->delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('api.authorizations.destroy');

        // 获取分类
        $api->get('categories', 'CategoriesController@index')
            ->name('api.categories.index');

        // 游客可以访问的接口
        $api->get('categories', 'CategoriesController@index')
            ->name('api.categories.index');

        $api->get('topics', 'TopicsController@index')
            ->name('api.topics.index');

        $api->get('user/{user}/topics', 'TopicsController@userIndex')
            ->name('api.users.topics.index');

        // 需要token验证的接口
        $api->group(['middleware' => 'api.auth'], function($api) {
            //当期登录用户信息
            $api->get('user', 'UsersController@me')
                ->name('api.user.show');

            // 图片资源
            $api->post('images', 'ImagesController@store')
                ->name('api.images.store');

            // 发布话题
            $api->post('topics', 'TopicsController@store')
                ->name('api.topics.store');

            // 修改话题
            $api->patch('topics/{topic}', 'TopicsController@update')
                ->name('api.topics.update');

            $api->delete('topics/{topic}', 'TopicsController@destroy')
                ->name('api.topics.destroy');

            // 编辑登录用户信息
            $api->patch('user', 'UsersController@update')
                ->name('api.user.update');
        });
    });
});
