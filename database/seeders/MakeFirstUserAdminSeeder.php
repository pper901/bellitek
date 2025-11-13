<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class MakeFirstUserAdminSeeder extends Seeder
{
    public function run()
    {
        $user = User::find(1);
        
        if ($user && !$user->is_admin) {
            $user->is_admin = true;
            $user->save();
        }
    }
}
