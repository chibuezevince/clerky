<?php

use App\Models\Clerking;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Ai\Enums\Lab;

return new class extends Migration {
    public function up(): void {
        Schema::create('summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Clerking::class)->unique()->constrained()->cascadeOnDelete();
            $table->longText('content')->nullable();
            $table->longText('edited_content')->nullable();
            $table->boolean('is_confirmed')->default(false);
            $table->enum('model_used', Lab::cases())->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('summaries');
    }
};
