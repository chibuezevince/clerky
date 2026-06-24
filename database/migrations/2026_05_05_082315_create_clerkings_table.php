<?php

use App\Enums\User\ClerkingStatus;
use App\Models\ClerkingTemplate;
use App\Models\ComplaintTemplate;
use App\Models\Patient;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('clerkings', function (Blueprint $table) {
            $table->id();
            $table->uuid('session_id')->unique()->index();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Patient::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Unit::class)->constrained();
            $table->foreignIdFor(ClerkingTemplate::class)->constrained();
            $table->enum('status', ClerkingStatus::cases())->default(ClerkingStatus::InProgress);
            $table->foreignId('current_section_id')->nullable()->constrained('clerking_sections')->nullOnDelete();
            $table->unsignedBigInteger('current_question_index')->default(1);
            $table->timestamp('completed_at')->nullable();
            $table->string('case_number')->unique();
            $table->timestamp('started_at')->nullable();
            $table->unsignedBigInteger('elapsed_seconds')->default(0);
            $table->timestamp('saved_at')->nullable();
            $table->json('question_history')->nullable();
            $table->boolean('is_processing')->default(false);
            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('clerkings');
    }
};
