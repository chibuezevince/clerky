<?php

use App\Models\ClerkingSection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('section_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ClerkingSection::class)->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->string('field_key')->nullable();
            $table->enum('input_type', ['text', 'textarea', 'select', 'boolean', 'scale', 'multi_select', 'number', 'key_value'])->default('textarea');
            $table->json('options')->nullable();

            $table->unsignedBigInteger('max_char')->default(255);

            $table->unsignedBigInteger('depends_on_section_question_id')->nullable();
            $table->unsignedBigInteger('depends_on_complaint_question_id')->nullable();

            $table->unsignedTinyInteger('minimum_age')->nullable();
            $table->unsignedTinyInteger('maximum_age')->nullable();
            $table->enum('sex', ['male', 'female', 'both'])->default('both');

            $table->string('depends_on_answer')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('section_questions');
    }
};
