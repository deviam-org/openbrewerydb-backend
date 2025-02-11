<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class OauthClientsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {


        DB::table('oauth_clients')->delete();

        DB::table('oauth_clients')->insert([
            0 =>
             [
                 'id' => '9e2ffa4f-e9d9-44b7-9162-c04a2b0a2831',
                 'user_id' => null,
                 'name' => 'Laravel Password Grant Client',
                 'secret' => '$2y$12$iqEspBf2/7Bf//9piNLSlOOSPG8okwFm11c4g5LtGgzWUdZmEq8g6',
                 'provider' => 'users',
                 'redirect' => 'http://localhost',
                 'personal_access_client' => false,
                 'password_client' => true,
                 'revoked' => false,
                 'created_at' => '2025-02-11 15:50:01',
                 'updated_at' => '2025-02-11 15:50:01',
             ],
        ]);


    }
}
