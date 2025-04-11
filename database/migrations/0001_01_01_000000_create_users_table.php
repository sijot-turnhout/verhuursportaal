<?php

declare(strict_types=1);

use App\Enums\UserGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->comment('Stores user account information, including authentication details, contact information, and activity timestamps');
            $table->id()->comment('UNique numerical identifier for each user, utomatically incremented.');
            $table->string('name')->comment('The user his/her full name, as displayed in the application');
            $table->string('user_group')->default(UserGroup::Leiding->value)->comment('The user his/her role or group within the system. defaults to "leiding" (leadership).');
            $table->string('email')->unique()->comment('The user his/her email address, used for login and notifications. Must be unique in the system.');
            $table->string('phone_number')->nullable()->comment('The user his/her password, for optional contact or verification.');
            $table->string('password')->comment('The user his hashed password, securely stroed for authentication.');
            $table->timestamp('email_verified_at')->comment('Timestamp indication when the user email address was been verified. It will be NULL when its not verified.')->nullable();
            $table->string('last_login_ip')->nullable()->comment('IP address form which the user last logged in, for security reasons and auditing.');
            $table->rememberToken()->comment('Token used for the "remember me" functionality, allowing persistent logins."');
            $table->dateTime('last_seen_at')->nullable()->comment('Timestamp of the user his last activity on the system, used for tracking active users.');
            $table->timestamp('created_at')->nullable()->comment('Timestamp when the user account was created. automatically set upon registration.');
            $table->timestamp('updated_at')->nullable()->comment('Timestamp when the user account was last updated. automatically updated on changes.');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table): void {
            $table->comment('Stores password reset tokens for users who have requested a password reset.');
            $table->string('email')->primary()->comment("User's email address associated with the reset token.");
            $table->string('token')->comment('The unique reset token generated for the user.');
            $table->timestamp('created_at')->nullable()->comment('Timestamp when the reset token was generated.');
        });

        Schema::create('sessions', function (Blueprint $table): void {
            $table->comment('Stores session data for users, including authentication and activity information.');
            $table->string('id')->primary()->comment('Unique session indentifier');
            $table->foreignId('user_id')->nullable()->index()->comment('Unique identifier of the associated user, if authenticated.');
            $table->string('ip_address', 45)->nullable()->comment('IP address of the user who initiated the session.');
            $table->text('user_agent')->nullable()->comment("User agent string of the user's browser or application.");
            $table->longText('payload')->comment('Serialized session data.');
            $table->integer('last_activity')->index()->comment('Timestamp (Unix) of the last activity in the session.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
