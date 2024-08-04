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
        Schema::create('notebooks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('user_id')
                ->constrained(table: 'users', indexName: 'notebooks_user_id_foreign')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('shared_with_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notebooks', function (Blueprint $table) {
            $table->dropForeign(['notebooks_user_id_foreign']);
        });

        Schema::dropIfExists('notebooks');
    }
};
