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
            $table->unsignedBigInteger(Config::foreignKey('user'));
            $table->unsignedBigInteger(Config::foreignKey('role'));

            $table->foreign(Config::foreignKey('user'))->references('id')->on(Config::table('users'))->onDelete('cascade');
            $table->foreign(Config::foreignKey('role'))->references('id')->on(Config::table('roles'))->onDelete('cascade');


            $table->primary([Config::foreignKey('user'), Config::foreignKey('role')])->unique();
        });

        Schema::create(Config::table('permissible'), function (Blueprint $table) {
            $table->unsignedBigInteger(Config::foreignKey('permission'));
            $table->morphs('permissible');

            $table->foreign(Config::foreignKey('permission'))
                ->references('id')
                ->on(Config::table('permissions'))
                ->onDelete('cascade');

            $table->unique([Config::foreignKey('permission'), 'permissible_id', 'permissible_type'],
                'model_permissible_permission_model_type_unique');
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
