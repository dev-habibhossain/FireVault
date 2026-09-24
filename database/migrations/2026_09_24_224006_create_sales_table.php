<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->unique()->constrained('listings')->restrictOnDelete();
            $table->foreignId('moderator_id')->constrained('users')->restrictOnDelete();
            $table->decimal('listed_price', 12, 2);
            $table->decimal('actual_price', 12, 2);
            $table->timestamp('sold_at')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['moderator_id', 'sold_at']);
            $table->index('sold_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
