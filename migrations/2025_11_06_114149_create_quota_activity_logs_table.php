<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quota_activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('action');
            $table->json('data');
            $table->unsignedBigInteger('agent_quota_policy_id');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quota_activity_logs');
    }
};
