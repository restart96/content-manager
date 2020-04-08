<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentManageGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('content-manager.database.groups_table'), function(Blueprint $table) {
            $table->increments('id')->comment('PK');
            $table->unsignedInteger('parent_id')->nullable()->comment('상위 콘텐츠 그룹 아이디');
            $table->unsignedInteger('left_id')->default(0)->comment('좌측 콘텐츠 그룹 아이디');
            $table->unsignedInteger('right_id')->default(0)->comment('우측 콘텐츠 그룹 아이디');
            $table->string('code', 64)->default('')->comment('코드');
            $table->string('name', 128)->nullable()->comment('그룹명');

            $table->timestamp('created_at')->useCurrent()->comment('생성 일시');
            $table->timestamp('updated_at')->nullable()->comment('수정 일시');

            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('content-manager.database.groups_table'));
    }
}
