<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    // activate foreign key when using the sqlite3 database;
    if (config('database.default') === 'sqlite') {
      DB::statement('PRAGMA foreign_keys = ON;');
    }
  }
}
