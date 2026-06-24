<?php

use App\Models\ClerkingSection;
use App\Models\ComplaintTemplate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('complaint_template_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ComplaintTemplate::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ClerkingSection::class)->constrained()->cascadeOnDelete();
            
            $table->text('question');
            $table->string('field_key')->nullable();
            $table->enum('input_type', ['text', 'textarea', 'select', 'boolean', 'scale', 'multi_select', 'number', 'key_value'])->default('textarea');

            $table->json('options')->nullable();
            $table->boolean('is_complaint')->default(false);
            $table->unsignedBigInteger('max_char')->default(255);
            $table->integer('order');
            $table->unsignedTinyInteger('minimum_age')->nullable();
            $table->unsignedTinyInteger('maximum_age')->nullable();
            $table->enum('sex', ['male', 'female', 'both'])->default('both');

            $table->unsignedBigInteger('depends_on_complaint_question_id')->nullable();
            $table->string('depends_on_answer')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('complaint_template_questions');
    }
};
