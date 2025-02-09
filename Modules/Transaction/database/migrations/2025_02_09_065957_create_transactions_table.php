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
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('ref_number', 10)->primary();
            $table->foreignIdFor(\Modules\Bank\Models\BankAccountCard::class, 'from_bank_account_card_number')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\Modules\Bank\Models\BankAccountCard::class, 'to_bank_account_card_number')->constrained()->cascadeOnDelete();
            $table->string('status', 30);
            $table->string('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
