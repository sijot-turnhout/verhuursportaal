<?php

declare(strict_types=1);

use App\Models\Key;
use App\Models\Local;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('keys', function (Blueprint $table): void {
            $table->id();
            $table->boolean('is_master_key')->default(false);
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type');
            $table->string('key_number')->nullable();
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('key_local', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Local::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Key::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_local');
        Schema::dropIfExists('keys');
    }
};
