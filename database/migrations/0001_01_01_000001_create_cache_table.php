<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cache', function (Blueprint $table): void {
            $table->comment('Stores cached data for improved application performance.');
            $table->string('key')->primary()->comment('Unique key for the cached data.');
            $table->mediumText('value')->comment('Serialized data to be cached.');
            $table->integer('expiration')->comment('Unix timestamp indicating when the cached data expires.');
        });

        Schema::create('cache_locks', function (Blueprint $table): void {
            $table->comment('Stores cache lock information to prevent race conditions during cache operations.');
            $table->string('key')->primary()->comment('Unique key identifying the locked cache resource.');
            $table->string('owner')->comment('Identifier of the process or entity that holds the lock.');
            $table->integer('expiration')->comment('Unix timestamp indicating when the lock expires.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
