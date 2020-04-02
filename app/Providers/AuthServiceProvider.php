<?php

namespace App\Providers;

use App\Models\Users;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        /**
        * Registry polici
        */
        // Register Pelanggan policy
        Gate::define('read-pelanggan', function ($users) {
          return $users->role == 'admin'|| $users->role == 'user';
        });
        Gate::define('read-pemesanan', function ($users) {
          return $users->role == 'admin'|| $users->role == 'user';
        });
        Gate::define('read-pembayaran', function ($users) {
          return $users->role == 'admin'|| $users->role == 'user';
        });
        Gate::define('read-users', function ($users) {
          return $users->role == 'admin'|| $users->role == 'user';
        });


        Gate::define('create-kamar', function ($users) {
          return $users->role == 'admin';
        });
        Gate::define('create-pelanggan', function ($users) {
          return $users->role == 'admin'|| $users->role == 'user';
        });
        Gate::define('create-users', function ($users) {
          return $users->role == 'admin';
        });
        Gate::define('create-pemesanan', function ($users) {
          return $users->role == 'admin'|| $users->role == 'user';
        });
        Gate::define('create-pembayaran', function ($users) {
          return $users->role == 'admin';
        });


        Gate::define('update-pelanggan', function ($users, $pelanggan) {
        if ($users->role == 'admin') {
        return true;
        } else if ($users->role == 'user') {
        return $pelanggan->id_pelanggan == $users->id;
        } else {
          return false;
        }
        });
        Gate::define('update-pemesanan', function ($users, $pemesanan) {
        if ($users->role == 'admin') {
        return true;
        } else if ($users->role == 'user') {
        return $pemesanan->id_pelanggan == $users->id;
        } else {
          return false;
        }
        });
        Gate::define('update-pembayaran', function ($users) {
          return $users->role == 'admin';
        });
        Gate::define('update-kamar', function ($users) {
          return $users->role == 'admin';
        });
        Gate::define('update-users', function ($users) {
        return $users->role == 'admin';
        });



        Gate::define('delete-pelanggan', function ($users) {
          return $users->role == 'admin';
        });
        Gate::define('delete-users', function ($users) {
          return $users->role == 'admin';
        });
        Gate::define('delete-pemesanan', function ($users) {
          return $users->role == 'admin';
        });
        Gate::define('delete-pembayaran', function ($users) {
          return $users->role == 'admin';
        });
        Gate::define('delete-kamar', function ($users) {
          return $users->role == 'admin';
        });


        Gate::define('show-pelanggan', function ($users, $pelanggan) {
        if ($users->role == 'admin') {
        return true;
        } else if ($users->role == 'user') {
        return $pelanggan->id_pelanggan == $users->id;
        } else {
          return false;
        }
        });
        Gate::define('show-pemesanan', function ($users, $pemesanan) {
        if ($users->role == 'admin') {
        return true;
        } else if ($users->role == 'user') {
        return $pemesanan->id_pelanggan == $users->id;
        } else {
          return false;
        }
        });
        Gate::define('show-pembayaran', function ($users, $pembayaran) {
        if ($users->role == 'admin') {
        return true;
        } else if ($users->role == 'user') {
        return $pembayaran->id_pelanggan == $users->id;
        } else {
          return false;
        }
        });


        /** Resgister policy end */
        // authentication start
        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return Users::where('api_token', $request->input('api_token'))->first();
            }
        });
    }
}
