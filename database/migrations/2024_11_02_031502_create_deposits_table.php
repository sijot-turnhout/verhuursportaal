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
        Schema::create('deposits', static function (Blueprint $table): void {
            $table->comment('This table contains all the information regarding the security deposits for leases.');
            $table->id()->comment('The unique identifier from the deposit record in the database system.');
            $table->foreignIdFor(Lease::class)->nullable()->comment('The unique identifier from the lease that is attached to the security deposit.')->constrained()->cascadeOnDelete();
            $table->string('status')->comment('The status of the allocation of the security deposit that has been paid by the tenant for his/her lease.');
            $table->decimal('paid_amount', 10, 2)->default('0.00')->comment('The amount that the tenant has paid to the organisation as security deposit for his/her lease.');
            $table->decimal('revoked_amount', 10, 2)->default('0.00')->comment('The amount that is withdrawn from the security deposit due to damages that occured on the lease.');
            $table->decimal('refunded_amount', 10, 2)->default('0.00')->comment('The amount that is successfully refunded to the tenant of the lease.');
            $table->text('note')->nullable()->comment('The note that holds information about the reason why the deposit has been fully/partially withdrawn.');
            $table->timestamp('paid_at')->nullable()->comment('The timestamp from the date that the security deposit for the lease has been paid.');
            $table->timestamp('refund_at')->nullable()->comment('The timestamp from the date when the deposit will been refunded.');
            $table->timestamp('refunded_at')->nullable()->comment('The timestamp from the data when the security has been refuned to the tenant.');
            $table->timestamps();
        });

        Schema::table('deposits', function (Blueprint $table): void {
            $table->timestamp('created_at')->comment('The timestamp from when the database entry has been created.')->change();
            $table->timestamp('updated_at')->comment('The timestamp from when the record has been last updated.')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
