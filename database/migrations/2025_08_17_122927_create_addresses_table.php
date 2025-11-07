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
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('addressid'); // Primary key
            $table->unsignedBigInteger('userid'); // Foreign key to users_admins

            $table->enum('addressType', ['Home', 'Office', 'Other'])->default('Home');
            $table->string('name', 100);
            $table->string('apartmentNo', 50)->nullable();
            $table->string('buildingName', 100)->nullable();
            $table->string('streetArea', 150)->nullable();
            $table->string('city', 100);
            $table->timestamp('createdAt')->useCurrent();
            // Foreign key constraint
            $table->foreign('userid')
                  ->references('userid')->on('users_admins')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
