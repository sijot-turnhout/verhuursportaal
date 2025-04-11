<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('tenants', static function (Blueprint $table): void {
            $table->comment('Stores core information about tenants, including contact details, names, and ban status. Used for tenant management and application functionality.');
            $table->id()->comment('Unique identifier for the tenant record, automatically generated.');
            $table->string('name')->virtualAs("concat(firstName, ' ', lastName)")->index()->comment('Virtual column: Concatenation of firstName and lastName, dynamically generated.');
            $table->string('firstName')->comment("Tenant's first name, required for record creation.");
            $table->string('lastName')->comment("Tenant's last name, required for record creation.");
            $table->string('email')->unique()->comment("Tenant's unique email address, used for communication.");
            $table->string('phone_number')->nullable()->comment("Tenant's optional phone number, used for contact.");
            $table->string('address')->nullable()->comment("Tenant's optional physical address.");
            $table->timestamp('banned_at')->nullable()->comment('Timestamp when the tenant was banned, if applicable; NULL if not banned.');
            $table->timestamp('created_at')->nullable()->comment('Timestamp when the tenant record was created, automatically set upon insertion.');
            $table->timestamp('updated_at')->nullable()->comment('Timestamp when the tenant record was last updated, automatically set on modifications.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
