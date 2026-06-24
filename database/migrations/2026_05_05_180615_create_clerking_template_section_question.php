<?php

use App\Models\ClerkingTemplate;
use App\Models\SectionQuestion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clerking_template_section_question', function (Blueprint $table) {
            $table->foreignIdFor(ClerkingTemplate::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(SectionQuestion::class)->constrained()->cascadeOnDelete();
            $table->decimal('order', 10, 5);
            $table->boolean('is_required')->default(true);
            $table->primary(['clerking_template_id', 'section_question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clerking_template_section_question');
    }
};
