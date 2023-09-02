<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    //
    $dafault_user_value = [
      'email_verified_at' => now(),
      'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
      'two_factor_secret' => null,
      'two_factor_recovery_codes' => null,
      'remember_token' => Str::random(10),
      'profile_photo_path' => null,
      'current_team_id' => null,
    ];
    DB::beginTransaction();
    try {
      $ust = User::create(
        array_merge(
          [
            'email' => 'ofaasd2@gmail.com',
            'name' => 'admin',
          ],
          $dafault_user_value
        )
      );
      $role_ust = Role::create(['name' => 'admin']);

      $ust->assignRole('ustadz');

      DB::commit();
    } catch (\Throwable $th) {
      //throw $th;
      DB::rollback();
    }
  }
}
