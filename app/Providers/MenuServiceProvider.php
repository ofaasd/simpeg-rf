<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
    $verticalMenuData = json_decode($verticalMenuJson);
    $horizontalMenuJson = file_get_contents(base_path('resources/menu/horizontalMenu.json'));
    $horizontalMenuData = json_decode($horizontalMenuJson);
    $ustadzMenuJson = file_get_contents(base_path('resources/menu/ustadzMenu.json'));
    $ustadzMenuData = json_decode($ustadzMenuJson);
    $keuanganMenuJson = file_get_contents(base_path('resources/menu/keuanganMenu.json'));
    $keuanganMenuData = json_decode($keuanganMenuJson);
    $tuMenuJson = file_get_contents(base_path('resources/menu/tuMenu.json'));
    $tuMenuData = json_decode($tuMenuJson);
    $umumMenuJson = file_get_contents(base_path('resources/menu/umumMenu.json'));
    $umumMenuData = json_decode($umumMenuJson);
    $kesehatanMenuJson = file_get_contents(base_path('resources/menu/kesehatanMenu.json'));
    $kesehatanMenuData = json_decode($kesehatanMenuJson);
    $adminInputMenuJson = file_get_contents(base_path('resources/menu/adminInputMenu.json'));
    $adminInputMenuData = json_decode($adminInputMenuJson);

    // Share all menuData to all the views
    \View::share('menuData',
    [
      $verticalMenuData,
      $horizontalMenuData,
      $ustadzMenuData,
      $keuanganMenuData,
      $tuMenuData,
      $umumMenuData,
      $kesehatanMenuData,
      $adminInputMenuData
    ]);
  }
}
