<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentManageItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('content-manager.database.items_table'), function(Blueprint $table) {
            $table->increments('id')->comment('PK');
            $table->unsignedInteger('category_id')->comment('콘텐츠 카테고리 아이디');
            $table->string('name', 128)->nullable()->comment('콘텐츠명');
            $table->string('description', 256)->nullable()->comment('콘텐츠 내용');
            $table->date('start_date')->nullable()->comment('시작 일시');
            $table->time('start_time')->nullable()->comment('시작 시간');
            $table->date('end_date')->nullable()->comment('종료 일시');
            $table->time('end_time')->nullable()->comment('종료 시간');
            $table->json('images')->nullable()->comment('이미지');
            $table->json('links')->nullable()->comment('링크');
            $table->unsignedTinyInteger('ord')->default(1)->comment('정렬순서');
            $table->enum('enable', ['N', 'Y'])->default('Y')->comment('활성화여부');

            $table->timestamp('created_at')->useCurrent()->comment('생성 일시');
            $table->timestamp('updated_at')->nullable()->comment('수정 일시');

            $table->foreign('category_id')
                  ->references('id')
                  ->on(config('content-manager.database.categories_table'))
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('content-manager.database.items_table'));
    }
}
