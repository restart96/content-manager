<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentManageCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('content-manager.database.categories_table'), function(Blueprint $table) {
            $table->increments('id')->comment('PK');
            $table->unsignedInteger('group_id')->comment('콘텐츠 그룹 아이디');
            $table->string('code', 64)->default('')->comment('코드');
            $table->string('name', 128)->nullable()->comment('카테고리명');
            $table->unsignedTinyInteger('ord')->default(1)->comment('정렬순서');

            $table->timestamp('created_at')->useCurrent()->comment('생성 일시');
            $table->timestamp('updated_at')->nullable()->comment('수정 일시');

            $table->unique(['group_id', 'code']);
            $table->foreign('group_id')
                  ->references('id')
                  ->on(config('content-manager.database.groups_table'))
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
        Schema::dropIfExists(config('content-manager.database.categories_table'));
    }
}
