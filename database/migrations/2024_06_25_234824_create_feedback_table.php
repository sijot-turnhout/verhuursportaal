<?php

declare(strict_types=1);

use App\Models\Feedback;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table): void {
            $table->id();
            $table->string('subject');
            $table->text('message');
            $table->timestamps();
        });

        Schema::table('leases', function (Blueprint $table): void {
            $table->foreignIdFor(Feedback::class)->nullable()->references('id')->on('feedback')->cascadeOnDelete();
            $table->timestamp('feedback_valid_until')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
