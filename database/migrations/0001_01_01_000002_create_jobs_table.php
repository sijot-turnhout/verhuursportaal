<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table): void {
            $table->comment('Stores jobs to be processed asynchronously by the application.');
            $table->id()->comment('Unique identifier for the job.');
            $table->string('queue')->index()->comment('Name of the queue the job belongs to.');
            $table->longText('payload')->comment('Serialized data representing the job to be executed.');
            $table->unsignedTinyInteger('attempts')->comment('Number of times the job has been attempted.');
            $table->unsignedInteger('reserved_at')->nullable()->comment('Unix timestamp when the job was reserved for processing.');
            $table->unsignedInteger('available_at')->comment('Unix timestamp when the job becomes available for processing.');
            $table->unsignedInteger('created_at')->comment('Unix timestamp when the job was created.');
        });

        Schema::create('job_batches', function (Blueprint $table): void {
            $table->comment('Stores information about job batches, allowing for tracking and management of groups of jobs.');
            $table->string('id')->primary()->comment('Unique identifier for the job batch.');
            $table->string('name')->comment('Descriptive name of the job batch.');
            $table->integer('total_jobs')->comment('Total number of jobs in the batch.');
            $table->integer('pending_jobs')->comment('Number of jobs in the batch that are still pending.');
            $table->integer('failed_jobs')->comment('Number of jobs in the batch that have failed.');
            $table->longText('failed_job_ids')->comment('Serialized array of job IDs that have failed.');
            $table->mediumText('options')->nullable()->comment('Serialized options or configuration for the batch.');
            $table->integer('cancelled_at')->nullable()->comment('Unix timestamp when the batch was cancelled, if applicable.');
            $table->integer('created_at')->comment('Unix timestamp when the batch was created.');
            $table->integer('finished_at')->nullable()->comment('Unix timestamp when the batch finished processing, if applicable.');
        });

        Schema::create('failed_jobs', function (Blueprint $table): void {
            $table->comment('Stores information about jobs that have failed during execution.');
            $table->id()->comment('Unique identifier for the failed job record.');
            $table->string('uuid')->unique()->comment('Unique identifier (UUID) of the failed job.');
            $table->text('connection')->comment('Connection string or details used for the failed job.');
            $table->text('queue')->comment('Name of the queue the failed job belonged to.');
            $table->longText('payload')->comment('Serialized data representing the failed job.');
            $table->longText('exception')->comment('Serialized exception details from the failed job.');
            $table->timestamp('failed_at')->useCurrent()->comment('Timestamp when the job failed.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
