<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('navigations', function (Blueprint $table) {
            $table->string('link')->nullable()->change();

            $table->string('type')->default('link')->after('name'); 
            
            $table->foreignId('category_id')
                ->nullable()
                ->after('type')
                ->constrained('categories')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('navigations', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['type', 'category_id']);
            $table->string('link')->nullable(false)->change();
        });
    }
};
