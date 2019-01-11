
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoriesTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parent_id')->nullable();

            $table->string('sku');
            $table->string('name');

            // // $table->unique('name');
            $table->unique('sku');

            $table->uuid('metric_id');
            $table->uuid('inventory_category_id');

            $table->text('description')->nullable();

            $table->foreign('metric_id')->references('id')->on('metrics')
                ->onUpdate('cascade');

            $table->foreign('inventory_category_id')->references('id')->on('inventory_categories')
                ->onUpdate('cascade');

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
        Schema::dropIfExists('inventories');
    }
}
