<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryMovementsTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('causable_id')->index();
            $table->string('causable_type');

            $table->uuid('inventory_id');
            $table->decimal('quantity', 16, 6)->default(0);
            // $table->decimal('cost', 8, 4)->default(0)->nullable();
            $table->string('remarks')->nullable();

            $table->foreign('inventory_id')->references('id')->on('inventories')
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
        Schema::dropIfExists('inventory_stock_movements');
    }
}
