<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayjsOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payjs_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 10)->default('weixin')->comment('支付方式');
            $table->string('out_trade_no', 50)->comment('商户订单号');
            $table->string('subject', 100)->comment('订单标题');
            $table->string('outer_tid', 30)->nullable()->comment('外部交易订单号');
            $table->string('transaction_tid', 30)->nullable()->comment('支付流水号');
            $table->decimal('total_fee',10)->default('0.00')->comment('订单金额');
            $table->tinyInteger('status')->default('1')->comment('0：已付款 1：等待付款');
            $table->timestamp('pay_at')->nullable()->comment('支付时间');
            $table->string('buyer_info')->nullable()->comment('支付者信息');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payjs_orders');
    }
}
