<?php

declare(strict_types=1);

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
        Schema::create('issues', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Local::class)->references('id')->on('locals')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'creator_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignIdFor(User::class)->nullable()->references('id')->on('users')->nullOnDelete();
            $table->string('status');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
