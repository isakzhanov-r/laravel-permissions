<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use IsakzhanovR\UserPermission\Helpers\Config;

class CreateUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create(Config::table('roles'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create(Config::table('permissions'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create(Config::table('user_roles'), function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('user_id')->references('id')->on(Config::table('users'))->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on(Config::table('roles'))->onDelete('cascade');


            $table->primary(['user_id', 'role_id'])->unique();
        });

        Schema::create(Config::table('has_permission'), function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');

            $table->string('model_type');
            $table->integer('model_id')->unsigned();
            $table->index(['model_id', 'model_type',], 'model_permissions_model_id_model_type_index');

            $table->foreign('permission_id')
                ->references('id')
                ->on(Config::table('permissions'))
                ->onDelete('cascade');

            $table->primary(['permission_id', 'model_id', 'model_type'],
                'model_has_permissions_permission_model_type_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Config::tables()->each(function ($table_name) {
            Schema::dropIfExists($table_name);
        });

        Schema::enableForeignKeyConstraints();
    }
}
