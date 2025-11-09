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
            $table->string('name', 30);
            $table->string('apartmentNo', 5)->nullable();
            $table->string('buildingName', 20)->nullable();
            $table->string('streetArea', 15)->nullable();
            $table->string('city', 20)->nullable();
            $table->string('latitude', 30)->nullable();
            $table->string('longitude', 30)->nullable();
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
