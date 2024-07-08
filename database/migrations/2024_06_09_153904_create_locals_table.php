<?php

declare(strict_types=1);

use App\Models\Lease;
use App\Models\Local;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('locals', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('storage_location')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('lease_local', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Local::class)->references('id')->on('locals')->cascadeOnDelete();
            $table->foreignIdFor(Lease::class)->references('id')->on('leases')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lease_local');
        Schema::dropIfExists('locals');
    }
};
