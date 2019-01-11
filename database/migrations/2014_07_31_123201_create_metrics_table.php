<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetricsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('metrics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parent_id')->nullable();

            $table->string('name');
            $table->string('symbol');
            $table->boolean('modifiable')->default(true);

            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('synced_at')->nullable();
        });
        $metrics = [
            [
                'id' => '1',
                'name' => 'Kilogram',
                'symbol' => 'kg',
                'modifiable' => false,
            ],
            [
                'id' => '2',
                'name' => 'Gram',
                'symbol' => 'g',
                'modifiable' => false,
            ],
            [
                'id' => '3',
                'name' => 'Litre',
                'symbol' => 'ℓ',
                'modifiable' => false,
            ],
            [
                'id' => '4',
                'name' => 'MilliLitre',
                'symbol' => 'mℓ',
                'modifiable' => false,
            ],
        ];
        DB::table('metrics')->insert($this->formatTimestamp($metrics));
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('metrics');
    }

    private function formatTimestamp(array $items)
    {
        return array_map(function ($item) {
            $time = now();
            $item['created_at'] = $time;
            $item['updated_at'] = $time;
            $item['synced_at'] = $time->addSecond();
            return $item;
        }, $items);
    }
}
