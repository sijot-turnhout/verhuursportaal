<?php

declare(strict_types=1);

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('leases', static function (Blueprint $table): void {
            $table->id();
            $table->string('group');
            $table->timestamp('arrival_date');
            $table->timestamp('departure_date');
            $table->integer('persons');
            $table->foreignIdFor(User::class, 'supervisor_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignIdFor(Tenant::class)->references('id')->on('tenants')->cascadeOnDelete();
            $table->string('status');
            $table->timestamp('metrics_registered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leases');
    }
};
