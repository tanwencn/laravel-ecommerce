<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockKeepingUnitsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_keeping_units', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('target_id')->unsigned()->comment('分类ID');
			$table->string('sku_code', 50)->default('');
			$table->string('sku_name', 80)->default('')->comment('SKU名称');
			$table->decimal('cost_price', 10)->unsigned()->default(0.00)->comment('成本价');
			$table->decimal('market_price', 10)->unsigned()->default(0.00)->comment('市场价');
			$table->decimal('price', 10)->unsigned()->default(0.00)->comment('商品价格');
			$table->integer('stock')->unsigned()->default(0)->comment('商品库存');
			$table->integer('sales')->unsigned()->default(0)->index('sales')->comment('销量');
			$table->timestamps();
			$table->softDeletes()->comment('删除时间');
			$table->unique(['target_id','sku_code'], 'target_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stock_keeping_units');
	}

}
