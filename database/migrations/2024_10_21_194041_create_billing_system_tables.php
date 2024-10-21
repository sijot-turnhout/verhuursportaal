<?php

use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Lease;
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
        Schema::create('quotations', static function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('status');
            $table->foreignIdFor(User::class, 'user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Lease::class, 'lease_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Tenant::class, 'reciever_id')->nullable()->references('id')->on('tenants')->nullOnDelete();
            $table->text('description')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });

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
            $table->foreignIdFor(Quotation::class)->nullable()->references('id')->on('quotations')->cascadeOnDelete();
            $table->foreignIdFor(Invoice::class)->nullable()->references('id')->on('invoices')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leases', function (Blueprint $table): void {
            $table->dropForeignIdFor(Quotation::class);
            $table->dropForeignIdFor(Invoice::class);
        });

        Schema::dropIfExists('billing_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('quotations');
    }
};
