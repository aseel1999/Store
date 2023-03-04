<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
           
            $table->id();
            $table->integer('user_id');
            $table->integer('address_id');
            //$table->integer('items');
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->integer('user_phone')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->string('street')->nullable();;
            $table->string('block')->nullable();;
            $table->integer('house_number')->nullable();;
            $table->integer('accomendation_type')->nullable()->comment('1->House,2->house_srt,3->gfd house,4->level house');
            $table->integer('delivery_mobile')->nullable();
            $table->text('extra_directions')->nullable();
            $table->integer('promo_code_id')->nullable();
            $table->string('promo_code_name')->nullable();
            $table->double('promo_code_amount')->nullable();
            $table->double('promo_code_type')->nullable()->comment('0->percentage , 1->amount');
           // $table->double('products_total');
           // $table->double('discount');
            $table->integer('payment_method')->comment('1-> online , 2-> cache_on_pickup');
            $table->integer('payment_status')->comment('1 ->yes , 0->no')->default(0);
            
           // $table->double('total');
            //$table->double('sub_total');
            $table->enum('status', ['1','2','3','4'])->comment('1->in_process, 2-> on_the_way , 3->completed , 4->canceled');
            $table->date('order_date');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
