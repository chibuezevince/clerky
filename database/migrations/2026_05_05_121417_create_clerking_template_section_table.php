<?php

use App\Models\ClerkingSection;
use App\Models\ClerkingTemplate;
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
        Schema::create('clerking_template_section', function (Blueprint $table) {
            $table->foreignIdFor(ClerkingTemplate::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ClerkingSection::class)->constrained()->cascadeOnDelete();
            $table->integer('order');
            $table->primary(['clerking_template_id', 'clerking_section_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clerking_template_section');
    }
};
