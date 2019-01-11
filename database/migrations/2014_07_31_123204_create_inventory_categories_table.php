<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->integer('depth')->nullable();
            $table->uuid('rgt')->nullable()->index();
            $table->uuid('lft')->nullable()->index();
            $table->uuid('parent_id')->nullable()->index();

            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('synced_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('inventory_categories');
    }
}
