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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/****** Funcoes ****/
Route::post('/qtd_home', 'ApiController@qtd_home')->name('api.qtdhome');
Route::post('/cities', 'ApiController@cities')->name('api.cities');
Route::get('/city/{city_id}', 'ApiController@city')->name('api.city');
Route::post('/setLatLng', 'ApiController@setLatLng')->name('api.setLatLng');

/****** Usuarios do APP ****/
Route::post('/putUser', 'UserAppController@putUser')->name('api.putUser');
Route::post('/validUser', 'UserAppController@validUser')->name('api.validUser');
Route::post('/getProfileUser', 'UserAppController@getProfileUser')->name('api.getProfileUser');
Route::post('/loginFace', 'FacebookController@loginApp')->name('api.loginFace');

/****** Clubs ****/
Route::post('/indClub', 'ClubIndicadoController@save')->name('api.indClub');
Route::post('/getClubs', 'ClubController@getClubs')->name('api.getClubs');
Route::post('/getClubsCity', 'ClubController@getClubsCity')->name('api.getClubsCity');
Route::post('/getClubsAllCity', 'ClubController@getClubsAllCity')->name('api.getClubsAllCity');
Route::post('/putUserFollowClub', 'UserFollowController@followClub')->name('api.followClub');
Route::post('/user_club/reset/', 'UserController@reset')->name('club.user.reset');
Route::post('/getClub', 'ClubController@getClub')->name('api.getClub');
Route::post('/getSchedule', 'ScheduleController@SevenDays')->name('api.getSchedule');
Route::post('/getGalleries', 'GalleryController@getGalleries')->name('api.getGalleries');
Route::post('/getPhotosGallery', 'GalleryPhotoController@getPhotosGallery')->name('api.getPhotosGallery');
Route::post('/getClubsAutoComplete', 'ClubController@getClubsAutoComplete')->name('api.getClubsAutoComplete');
Route::post('/getNearestClubs', 'ClubController@getNearestClubs')->name('api.getNearestClubs');
Route::post('/getRankings', 'RankingController@getRankings')->name('api.getRankings');
Route::post('/getTournaments', 'TournamentController@getTournaments')->name('api.getTournaments');
Route::post('/getPromotion', 'TournamentController@getPromotion')->name('api.getPromotion');
Route::post('/putUserSubscriptionTournament', 'TournamentController@putUserSubscriptionTournament')->name('api.putUserSubscriptionTournament');
Route::post('/getUserSubscriptionTournament', 'TournamentController@getUserSubscriptionTournament')->name('api.getUserSubscriptionTournament');
Route::post('/getBannerPromo', 'ApiController@getBannerPromo')->name('api.getBannerPromo');
Route::post('/getClubMessages', 'MessageController@getClubMessages')->name('api.getClubMessages');
Route::post('/getUserMessages', 'MessageController@getUserMessages')->name('api.getUserMessages');
Route::post('/getMessage', 'MessageController@getMessage')->name('api.getMessage');
Route::post('/sendMessage', 'MessageController@sendMessage')->name('api.sendMessage');
Route::get('/sendMessage2', 'MessageController@sendMessage')->name('api.sendMessage2');
Route::post('/deleteMessage', 'MessageController@deleteMsgUser')->name('api.deleteMsgUser');
Route::post('/deleteAllMessages', 'MessageController@deleteAllMessages')->name('api.deleteAllMessages');


/***** Pagamento da Licença *****/
Route::post('/callback/lic', 'LicenseController@callback')->name('api.pay.lic');
Route::post('/callback/msg', 'LicenseController@callback')->name('api.pay.msg');
//Route::get('/getPay/{lic}', 'LicenseController@getPay')->name('api.club.lic.pay');

//Route::post('/testeFile', 'ApiController@testeFile')->name('api.testeFile');


//Route::post('/testeFile', 'ApiController@testeFile')->name('api.testeFile');

Route::post('/pagseguro/notification', 'LicenseController@callback')->name('pagseguro.notification');
Route::post('/pagseguro/redirect', 'LicenseController@index')->name('pagseguro.redirect');