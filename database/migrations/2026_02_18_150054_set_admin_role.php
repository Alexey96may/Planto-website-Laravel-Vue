<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $user = User::first();
        
        if ($user) {
            $user->update(['role' => 'admin']);
        }
    }

    public function down(): void
    {
        $user = User::first();
        if ($user) {
            $user->update(['role' => 'user']);
        }
    }
};
