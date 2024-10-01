<?php

declare(strict_types=1);

use App\Models\ArticleCategory;
use App\Models\Articles;
use App\Models\Local;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('article_categories', function (Blueprint $table): void {
            $table->id()->comment('Uniek identificatienummer voor elke categorie');
            $table->string('name')->comment('Naam van de categorie. (Bijv. Tenten, Keuken, Kampvuur)');
            $table->string('description')->nullable()->comment('Eventuele beschrijving van de categorie.');
            $table->timestamps();
        });

        Schema::create('articles', static function (Blueprint $table): void {
            $table->id()->comment('Unieke identificatienummer voor elk materieel stuk in de inventaris.');
            $table->boolean('is_loanable')->default(true)->comment('Indicator dat het inventaris artikel uitleenbaar is');
            $table->string('name')->comment('Naam van het materieel (bijv. Tent, kampvuurrooster, zeil)');
            $table->foreignIdFor(Local::class, 'storage_location_id')->comment('Verwijziging naar de opslagplaatsen tabel voor de artikelen')->references('id')->on('locals')->cascadeOnDelete();
            $table->text('description')->comment('Beschrijving van het materieel. (bijv. afmetingen, capaciteit)')->nullable();
            $table->integer('total_amount')->comment('Totaal aantal van dit type materieel in de inventaris.')->default('1');
            $table->integer('in_stock')->comment('Aantal beschikbaar voor uitleen/gebruik op dit moment.')->nullable();
            $table->timestamps();
        });

        Schema::create('article_inspections', static function (Blueprint $table): void {
            $table->id()->comment('Uniek identificatienummer voor elke controle/inspectie');
            $table->foreignIdFor(User::class)->nullable()->comment('Verwijzing naar de persoon die de controle heeft uitgevoerd.')->references('id')->on('users')->nullOnDelete();
            $table->foreignIdFor(Articles::class)->comment('Verwijzing naar het gecontroleerde materieel in de materieel inventaris')->references('id')->on('articles')->cascadeOnDelete();
            $table->timestamp('inspection_at')->nullable()->comment('Datum waarop de controle is uitgevoerd');
            $table->string('result')->comment('Resultaat van de controle');
            $table->text('comments')->comment('Eventuele opmerkingen of aanbevelingen van de controle.')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_articles_categories', static function (Blueprint $table): void {
            $table->foreignIdFor(ArticleCategory::class)->constrained(table: 'article_categories')->cascadeOnDelete()->comment('Verwijzig naar de categorieen tabel voor de koppeling');
            $table->foreignIdFor(Articles::class)->constrained(table: 'articles')->cascadeOnDelete()->comment('Verwijzing naar de artikelen table voor koppeling.');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_categories');
        Schema::dropIfExists('inventory_inspections');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('inventory_articles_categories');
    }
};
