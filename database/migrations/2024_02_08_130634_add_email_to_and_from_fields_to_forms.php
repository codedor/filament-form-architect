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
        Schema::table('forms', function (Blueprint $table) {
            $table->dropColumn('email');

            $table->after('max_submissions_message', function (Blueprint $table) {
                $table->json('email_to')->nullable();
                $table->string('email_from')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->text('email')->nullable();

            $table->dropColumn('email_to');
            $table->dropColumn('email_from');
        });
    }
};
