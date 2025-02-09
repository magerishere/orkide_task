<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->string('code', 3)->primary();
            $table->string('country_code', 2)->comment('ISO alpha-2');
            $table->string('prefix_structure', 2);
            $table->string('prefix_card_number', 6);
            $table->string('name');
            $table->string('name_fa');
            $table->timestamps();
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->string('number', 18)->primary();
            $table->foreignIdFor(\Modules\Bank\Models\Bank::class)->constrained()->cascadeOnDelete();
            $table->string('user_mobile', 11)->index();
            $table->foreign('user_mobile')->references('mobile')->on(app(\App\Models\User::class)->getTable())->cascadeOnDelete();
            $table->string('status', 30);
            $table->string('type', 30);
            $table->string('balance');
            $table->timestamps();
        });

        Schema::create('bank_account_cards', function (Blueprint $table) {
            $table->string('number', 16)->primary();
            $table->foreignIdFor(\Modules\Bank\Models\BankAccount::class)->constrained()->cascadeOnDelete();
            $table->string('status', 30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_account_cards');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('banks');
    }
};
