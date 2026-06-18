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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('survey_file')->nullable();
            $table->foreignId('dosen_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['belum_mengajukan', 'pending', 'approved', 'rejected'])->default('belum_mengajukan');
            $table->string('laporan_file')->nullable();
            $table->integer('nilai_capstone')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');
        });
        Schema::dropIfExists('groups');
    }
};
