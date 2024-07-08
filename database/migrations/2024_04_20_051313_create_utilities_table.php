<?php

declare(strict_types=1);

use App\Models\Lease;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('utilities', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Lease::class)->references('id')->on('leases')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('start_value', 10, 3)->default('0');
            $table->decimal('end_value', 10, 3)->default('0');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('usage_total', 10, 3)->virtualAs('end_value - start_value');
            $table->decimal('billing_amount', 8, 2)->virtualAs('usage_total * unit_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilities');
    }
};
