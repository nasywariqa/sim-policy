<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function(Blueprint $table) {
            $table->boolean('is_active')->default(true);
            $table->boolean('is_super_admin')->default(false);
        });
        Schema::create('roles',function(Blueprint $t){$t->id();$t->string('slug')->unique();$t->string('name');});
        Schema::create('permissions',function(Blueprint $t){$t->id();$t->string('slug')->unique();$t->string('name');});
        Schema::create('permission_role',function(Blueprint $t){
            $t->foreignId('role_id')->constrained()->cascadeOnDelete();
            $t->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $t->primary(['role_id','permission_id']);
        });
        Schema::create('periods',function(Blueprint $t){
            $t->id();$t->string('name')->unique();$t->date('starts_on');$t->date('ends_on');
            $t->boolean('is_active')->default(false);$t->timestamps();
        });
        Schema::create('departments',function(Blueprint $t){$t->id();$t->string('name')->unique();});
        Schema::create('memberships',function(Blueprint $t){
            $t->id();$t->foreignId('user_id')->constrained()->restrictOnDelete();
            $t->foreignId('role_id')->constrained()->restrictOnDelete();
            $t->foreignId('period_id')->constrained()->restrictOnDelete();
            $t->foreignId('department_id')->nullable()->constrained()->restrictOnDelete();
            $t->boolean('is_chairperson')->default(false);$t->timestamps();
            $t->unique(['user_id','period_id']);
        });
        Schema::create('membership_permission_overrides',function(Blueprint $t){
            $t->id();$t->foreignId('membership_id')->constrained()->cascadeOnDelete();
            $t->foreignId('permission_id')->constrained()->restrictOnDelete();
            $t->string('effect',8);$t->unique(['membership_id','permission_id'],'member_perm_unique');
        });
        Schema::create('program_works',function(Blueprint $t){
            $t->id();$t->foreignId('period_id')->constrained()->restrictOnDelete();
            $t->foreignId('department_id')->constrained()->restrictOnDelete();
            $t->foreignId('created_by_membership_id')->constrained('memberships')->restrictOnDelete();
            $t->string('name');$t->text('purpose');$t->text('description')->nullable();
            $t->date('scheduled_at')->nullable();$t->decimal('budget',14,2)->default(0);
            $t->string('target')->nullable();$t->string('status',20)->default('draft');
            $t->timestamps();$t->index(['period_id','status']);
        });
        Schema::create('program_work_pics',function(Blueprint $t){
            $t->id();$t->foreignId('program_work_id')->constrained()->cascadeOnDelete();
            $t->foreignId('membership_id')->constrained()->restrictOnDelete();
            $t->unique(['program_work_id','membership_id'],'work_pic_unique');
        });
        Schema::create('program_notes',function(Blueprint $t){
            $t->id();$t->foreignId('program_work_id')->constrained()->restrictOnDelete();
            $t->foreignId('membership_id')->constrained()->restrictOnDelete();
            $t->text('content');$t->timestamp('created_at')->useCurrent();
        });
        Schema::create('program_decisions',function(Blueprint $t){
            $t->id();$t->foreignId('program_work_id')->constrained()->restrictOnDelete();
            $t->foreignId('membership_id')->constrained()->restrictOnDelete();
            $t->string('decision',15);$t->text('note')->nullable();
            $t->timestamp('created_at')->useCurrent();
        });
        Schema::create('saw_criteria',function(Blueprint $t){
            $t->id();$t->foreignId('period_id')->constrained()->restrictOnDelete();
            $t->string('code',10);$t->string('name');$t->string('type',8);
            $t->string('scale',10)->default('score');$t->decimal('weight',7,4);
            $t->timestamps();$t->unique(['period_id','code']);
        });
        Schema::create('saw_assessments',function(Blueprint $t){
            $t->id();$t->foreignId('program_work_id')->constrained()->restrictOnDelete();
            $t->foreignId('saw_criterion_id')->constrained()->restrictOnDelete();
            $t->foreignId('membership_id')->constrained()->restrictOnDelete();
            $t->decimal('value',14,4);$t->timestamps();
            $t->unique(['program_work_id','saw_criterion_id','membership_id'],'saw_assessment_unique');
        });
        Schema::create('saw_runs',function(Blueprint $t){
            $t->id();$t->foreignId('period_id')->constrained()->restrictOnDelete();
            $t->foreignId('membership_id')->constrained()->restrictOnDelete();
            $t->json('input_snapshot');$t->timestamps();
        });
        Schema::create('saw_results',function(Blueprint $t){
            $t->id();$t->foreignId('saw_run_id')->constrained()->cascadeOnDelete();
            $t->foreignId('program_work_id')->constrained()->restrictOnDelete();
            $t->decimal('score',14,8);$t->unsignedInteger('rank');
            $t->unique(['saw_run_id','program_work_id'],'saw_result_unique');
        });
    }

    public function down(): void
    {
        foreach (['saw_results','saw_runs','saw_assessments','saw_criteria','program_decisions','program_notes','program_work_pics','program_works','membership_permission_overrides','memberships','departments','periods','permission_role','permissions','roles'] as $name) {
            Schema::dropIfExists($name);
        }
        Schema::table('users',function(Blueprint $table){$table->dropColumn(['is_active','is_super_admin']);});
    }
};
