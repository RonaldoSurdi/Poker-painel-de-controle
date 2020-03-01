<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    /*** se veio de algum outro dominio direciona para o PokerClubs ***/
    if (env('APP_ENV','local')=='production') {
        if (!str_contains(url()->full(), 'pokerclubsapp'))
            return view('open');
    }

    /***Abrir home do poker ***/
    return view('home');
});

Route::get('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/premium');
});

Route::get('/msg', function () {
    return view('msg');
});

Auth::routes();

Route::get('register', function () {
    Session::put('Saviso','Cadastre-se pelo APP!');
    return redirect('/');
});

Route::get('/home', function () {
    return redirect('/club');
});

Route::get('/premium', function () {
    return view('premium');
});

Route::post('/sendcontact', 'HomeController@contato')->name('home.contato');

Route::get('loginFacebook', 'FacebookController@login');
Route::get('facebook', 'FacebookController@pageFacebook');

/**** Link de confirmação enviado por e-mail quando se cadastra pelo app ***/
Route::get('/confirm/{idbase}', 'UserAppController@confirm')->name('user.confirm');
Route::get('/logar/{cad}', 'UserAppController@logar')->name('user.logar');

Route::group(['prefix' => 'map', 'middleware' => 'check.user'], function () {
    Route::get('/', 'HomeController@index')->name('app.home');
    Route::post('/raio', 'HomeController@raio')->name('user.raio');
});



Route::post('/newcad', 'ClubController@newcad')->name('new.club');

Route::group(['prefix' => 'lic', 'middleware' => 'check.club'], function () {
    Route::get('/', 'LicenseController@index')->name('club.lic');
    Route::get('/free', 'LicenseController@free')->name('club.lic.free');
    Route::post('/premium1', 'LicenseController@premium1')->name('club.lic.premium1');
    Route::post('/premium2', 'LicenseController@premium2')->name('club.lic.premium2');
    Route::post('/premium', 'LicenseController@premium')->name('club.lic.premium');
    Route::get('/pay/{cad}', 'LicenseController@pay')->name('club.lic.pay');
    Route::post('/setCadData/', 'LicenseController@setCadData')->name('club.lic.setCadData');
});

Route::group(['prefix' => 'club', 'middleware' => 'check.lic'], function () {
//    Route::get('/', 'ClubController@index')->name('club.home');
    Route::get('/', 'ClubController@dados')->name('club.home');
    Route::get('/dados', 'ClubController@dados')->name('club.dados');

    Route::post('/setLatLng/', 'ClubController@setLatLng')->name('club.setlatlng');
    Route::post('/setLive/', 'ClubController@setLinkLive')->name('club.setLinkLive');
    Route::post('/setInfo/', 'ClubController@setInfo')->name('club.setInfo');
    Route::post('/setCad/', 'ClubController@setCad')->name('club.setCad');

    Route::post('/setContract/', 'ClubController@setContract')->name('club.setcontract');
    Route::post('/setTerms/', 'ClubController@setTerms')->name('club.setterms');


    Route::post('/logo/save', 'ClubController@SetLogo')->name('club.logo.add');
    Route::get('/logo/del', 'ClubController@DelLogo')->name('club.logo.del');

    Route::group(['prefix' => 'gal'], function () {
        Route::get('/', 'GalleryController@index')->name('club.gal');
        Route::get('/new', 'GalleryController@create')->name('club.gal.new');
        Route::get('/edit/{cad}', 'GalleryController@edit')->name('club.gal.edit');
        Route::post('/save', 'GalleryController@store')->name('club.gal.save');
        Route::post('/add', 'GalleryPhotoController@AddPhotoGal')->name('club.gal.add');
        Route::post('/all', 'GalleryPhotoController@photos')->name('club.gal.all');
        Route::get('/del/{cad}', 'GalleryController@destroy')->name('club.gal.del');

        Route::post('/search', 'GalleryController@search')->name('club.gal.search');
    });

    Route::group(['prefix' => 'foto'], function () {
        Route::post('/add', 'GalleryPhotoController@AddPhotoClub')->name('club.photo.add');
        Route::post('/desc', 'GalleryPhotoController@SetDescPhoto')->name('club.photo.desc');
        Route::get('/del/{cad}', 'GalleryPhotoController@DelPhoto')->name('club.photo.del');
    });

    Route::group(['prefix' => 'schedule'], function () {
        Route::get('/', 'ScheduleController@index')->name('club.schedule');
    });


    Route::group(['prefix' => 'rank'], function () {
        Route::get('/', 'RankingController@index')->name('club.rank');
        Route::get('/new', 'RankingController@create')->name('club.rank.new');
        Route::get('/edit/{cad}', 'RankingController@edit')->name('club.rank.edit');
        Route::post('/save', 'RankingController@store')->name('club.rank.save');
        Route::get('/del/{cad}', 'RankingController@destroy')->name('club.rank.del');
        Route::get('/show/{cad}', 'RankingController@show')->name('club.rank.show');

        Route::post('/img/save', 'RankingPlayerController@SetImagem')->name('club.rank.img.add');
        Route::post('/img/del', 'RankingPlayerController@DelImagem')->name('club.rank.img.del');

        Route::post('/tournament', 'RankingController@list_tourn')->name('club.rank.list2');

        Route::post('/player/add', 'RankingPlayerController@add')->name('club.rank.player.add');
        Route::post('/player/del', 'RankingPlayerController@destroy')->name('club.rank.player.del');
        Route::post('/players', 'RankingPlayerController@index')->name('club.rank.players');

        Route::post('/search', 'RankingController@search')->name('club.rank.search');
        Route::post('/step/list', 'RankingController@steps')->name('club.rank.step.list');

        Route::post('/points', 'RankingPointController@store')->name('club.rank.points');
        Route::post('/positions', 'RankingPointController@positions')->name('club.rank.positions');
    });

    Route::group(['prefix' => 'blind'], function () {
        Route::get('/', 'BlindController@index')->name('club.blind');
        Route::get('/new', 'BlindController@create')->name('club.blind.new');
        Route::post('/newsleep', 'BlindController@newsleep')->name('club.blind.newsleep');
        Route::get('/edit/{cad}', 'BlindController@edit')->name('club.blind.edit');
        Route::post('/save', 'BlindController@store')->name('club.blind.save');
        Route::get('/del/{cad}', 'BlindController@destroy')->name('club.blind.del');
        Route::get('/show/{cad}', 'BlindController@show')->name('club.blind.show');
        Route::post('/img/save2', 'TournamentController@SetImagemBlind')->name('club.blind.img.add2');

        Route::post('/img/save', 'BlindPlayerController@SetImagem')->name('club.blind.img.add');
        Route::post('/img/del', 'BlindPlayerController@DelImagem')->name('club.blind.img.del');
        Route::post('/podium', 'BlindPlayerController@podium')->name('club.blind.podium');
        Route::post('/positions', 'BlindPlayerController@positions')->name('club.blind.positions');
        Route::post('/mesas', 'BlindPlayerController@mesas')->name('club.blind.mesas');

        Route::post('/tournament', 'BlindController@list_tourn')->name('club.blind.list2');

        Route::post('/award/add', 'BlindAwardController@add')->name('club.blind.award.add');
        Route::post('/award/del', 'BlindAwardController@destroy')->name('club.blind.award.del');
        Route::post('/award/save', 'BlindAwardController@store')->name('club.blind.award.save');
        Route::post('/awards', 'BlindAwardController@index')->name('club.blind.awards');

        Route::post('/round/add', 'BlindRoundController@add')->name('club.blind.round.add');
        Route::post('/round/del', 'BlindRoundController@destroy')->name('club.blind.round.del');
        Route::post('/round/save', 'BlindRoundController@store')->name('club.blind.round.save');
        Route::post('/round/move', 'BlindRoundController@move')->name('club.blind.round.move');
        Route::post('/rounds', 'BlindRoundController@index')->name('club.blind.rounds');
        Route::post('/listrounds', 'BlindRoundController@listrounds')->name('club.blind.listrounds');

        Route::post('/player/add', 'BlindPlayerController@add')->name('club.blind.player.add');
        Route::post('/player/addusersapp', 'BlindPlayerController@addusersapp')->name('club.blind.player.addusersapp');
        Route::post('/player/del', 'BlindPlayerController@destroy')->name('club.blind.player.del');
        Route::post('/player/passaport', 'BlindPlayerController@passaport')->name('club.blind.player.passaport');
        Route::post('/player/active', 'BlindPlayerController@active')->name('club.blind.player.active');
        Route::post('/player/info', 'BlindPlayerController@info')->name('club.blind.player.info');
        Route::post('/player/saiu', 'BlindPlayerController@saiu')->name('club.blind.player.saiu');
        Route::post('/player/save', 'BlindPlayerController@store')->name('club.blind.player.save');
        Route::post('/players', 'BlindPlayerController@index')->name('club.blind.players');
        Route::post('/mesasrefresh', 'BlindPlayerController@mesasrefresh')->name('club.blind.mesasrefresh');
        Route::post('/blindstat', 'BlindPlayerController@blindstat')->name('club.blind.blindstat');
        Route::post('/player/sortearmesas', 'BlindPlayerController@sortearmesas')->name('club.blind.player.sortearmesas');

        Route::post('/search', 'BlindController@search')->name('club.blind.search');
        Route::post('/step/list', 'BlindController@steps')->name('club.blind.step.list');

    });

    Route::group(['prefix' => 'torn'], function () {
        Route::get('/', 'TournamentController@index')->name('club.torn');
        Route::get('/new', 'TournamentController@create')->name('club.torn.new');
        Route::get('/newW/{day}', 'TournamentController@createWeek')->name('club.torn.newW');
        Route::get('/edit/{cad}', 'TournamentController@edit')->name('club.torn.edit');
        Route::post('/save', 'TournamentController@store')->name('club.torn.save');
        Route::get('/del/{cad}', 'TournamentController@destroy')->name('club.torn.del');
        Route::get('/show/{cad}', 'TournamentController@show')->name('club.torn.show');

        Route::get('/insc/{cad}', 'TournamentController@ListaInscritos')->name('club.torn.insc');

        Route::post('/img/save', 'TournamentController@SetImagem')->name('club.torn.img.add');
        Route::post('/img/save2', 'TournamentController@SetImagem2')->name('club.torn.img.add2');
        Route::post('/img/del', 'TournamentController@DelImagem')->name('club.torn.img.del');

        Route::post('/date/add', 'TournamentDateController@newDate')->name('club.torn.date.add');
        Route::post('/date/all', 'TournamentDateController@getDates')->name('club.torn.date.all');
        Route::post('/search', 'TournamentController@search')->name('club.torn.search');

    });

    Route::group(['prefix' => 'msg'], function () {
        Route::get('/', 'MessageController@index')->name('club.msg');
        Route::get('/new', 'MessageController@create')->name('club.msg.new');
        Route::get('/edit/{cad}', 'MessageController@edit')->name('club.msg.edit');
        Route::post('/save', 'MessageController@store')->name('club.msg.save');
        Route::get('/del/{cad}', 'MessageController@destroy')->name('club.msg.del');
        Route::get('/show/{cad}', 'MessageController@show')->name('club.msg.show');

        Route::post('/img/save', 'MessageController@SetImagem')->name('club.msg.img.add');
        Route::post('/img/del', 'MessageController@DelImagem')->name('club.msg.img.del');

        Route::post('/search', 'MessageController@search')->name('club.msg.search');
        Route::post('/calc', 'MessageUserController@calcular')->name('club.msg.calc');

        Route::get('/enviar/{cad}', 'MessageController@enviar')->name('club.msg.enviar');

    });
});