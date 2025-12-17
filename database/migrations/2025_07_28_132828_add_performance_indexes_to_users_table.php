<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexesToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add indexes for commonly searched columns
            $table->index('first_name');
            $table->index('last_name');
            $table->index('email');
            $table->index('CNIC');
            $table->index('gender');
            
            // Composite indexes for name searches
            $table->index(['first_name', 'last_name']);
            
            // Full-text search indexes for better performance
            $table->fullText(['first_name', 'last_name'], 'users_name_fulltext');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop indexes in reverse order
            $table->dropFullText(['first_name', 'last_name']);
            $table->dropIndex(['first_name', 'last_name']);
            $table->dropIndex(['gender']);
            $table->dropIndex(['CNIC']);
            $table->dropIndex(['email']);
            $table->dropIndex(['last_name']);
            $table->dropIndex(['first_name']);
        });
    }
}
