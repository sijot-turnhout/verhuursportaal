<?php

declare(strict_types=1);

use App\Models\Invoice;
use App\Models\Lease;
use App\Models\Tenant;
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
        Schema::create('invoices', function (Blueprint $table): void {
            $table->id();
            $table->string('payment_reference')->unique()->comment('Factuur referentie');
            $table->string('status')->nullable();
            $table->foreignIdFor(User::class, 'creator_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignIdFor(Lease::class, 'lease_id')->references('id')->on('leases')->cascadeOnDelete();
            $table->foreignIdFor(Tenant::class, 'customer_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->text('description')->nullable()->comment('Beschrijving');
            $table->timestamp('due_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });

        Schema::table('leases', function (Blueprint $table): void {
            $table->foreignIdFor(Invoice::class)->nullable()->references('id')->on('invoices')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
