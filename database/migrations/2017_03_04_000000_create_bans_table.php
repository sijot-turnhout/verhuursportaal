<?php

/*
 * This file is part of Laravel Ban.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('bans', function (Blueprint $table): void {
            $table->comment('Stores ban records for various entities within the application.');
            $table->increments('id')->comment('Unique identifier for the ban record.');
            $table->morphs('bannable');
            $table->nullableMorphs('created_by');
            $table->text('comment')->nullable()->comment('Optional comment or reason for the ban.');
            $table->timestamp('expired_at')->nullable()->comment('Timestamp when the ban expires.');
            $table->timestamp('deleted_at')->nullable()->comment('Timestamp when the ban was soft-deleted.');
            $table->timestamp('created_at')->nullable()->comment('Timestamp when the ban was registered.');
            $table->timestamp('updated_at')->nullable()->comment('Timestamp when the ban registration was last updated.');

            $table->index('expired_at');
        });

        Schema::table('bans', function (Blueprint $table): void {
            $table->string("bannable_type")->comment('Type of entity being banned (e.g., User).')->change();
            $table->unsignedBigInteger("bannable_id")->comment('Unique identifier of the entity being banned.')->change();
            $table->string("created_by_type")->nullable()->comment('Type of entity that created the ban (e.g., Admin, Moderator).')->change();
            $table->unsignedBigInteger("created_by_id")->nullable()->comment('Unique identifier of the entity that created the ban registration.')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('bans');
    }
}
