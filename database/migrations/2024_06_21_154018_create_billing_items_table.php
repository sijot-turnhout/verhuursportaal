<?php

declare(strict_types=1);

use App\Models\Invoice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('billing_items', function (Blueprint $table): void {
            $table->id();
            $table->morphs('billingdocumentable', 'billing_document_index');
            $table->boolean('type')->nullable();
            $table->string('name');
            $table->decimal('quantity', total: 16);
            $table->decimal('unit_price');
            $table->decimal('total_price', total: 16)->storedAs('unit_price * quantity')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_items');
    }
};
