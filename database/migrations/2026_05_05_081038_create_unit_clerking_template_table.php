<?php

use App\Models\ClerkingTemplate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_clerking_template', function (Blueprint $table) {
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ClerkingTemplate::class)->constrained()->cascadeOnDelete();
            $table->boolean('is_default')->default(false);
            $table->primary(['unit_id', 'clerking_template_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_clerking_template');
    }
};
