<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('telegram_id');
            $table->string('nickname')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('language_code')->default('ru');
            $table->string('phone_number')->nullable();
            $table->string('balance')->default('0');
            $table->string('referral')->nullable();
            $table->boolean('is_banned')->default(false);
            $table->timestamps();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->index(['telegram_id', 'phone_number', 'is_banned', 'balance']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
