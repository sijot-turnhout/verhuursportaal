<?php

use App\Models\Lease;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', static function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('status');
            $table->foreignIdFor(User::class, 'user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Lease::class, 'lease_id')->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });

        Schema::table('leases', function (Blueprint $table): void {
            $table->foreignIdFor(Quotation::class)->nullable()->references('id')->on('quotations')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
