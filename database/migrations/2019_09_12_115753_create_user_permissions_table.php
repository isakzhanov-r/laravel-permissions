<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use IsakzhanovR\Permissions\Helpers\Configable;

class CreateUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create(Configable::table('roles'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create(Configable::table('permissions'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create(Configable::table('user_roles'), function (Blueprint $table) {
            $table->unsignedBigInteger(Configable::foreignKey('user'));
            $table->unsignedBigInteger(Configable::foreignKey('role'));

            $table->foreign(Configable::foreignKey('user'))->references('id')->on(Configable::table('users'))->onDelete('cascade');
            $table->foreign(Configable::foreignKey('role'))->references('id')->on(Configable::table('roles'))->onDelete('cascade');


            $table->primary([Configable::foreignKey('user'), Configable::foreignKey('role')])->unique();
        });

        Schema::create(Configable::table('permissible'), function (Blueprint $table) {
            $table->unsignedBigInteger(Configable::foreignKey('permission'));
            $table->morphs('permissible');

            $table->foreign(Configable::foreignKey('permission'))
                ->references('id')
                ->on(Configable::table('permissions'))
                ->onDelete('cascade');

            $table->unique([Configable::foreignKey('permission'), 'permissible_id', 'permissible_type'],
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

        Configable::tables()->each(function ($table_name) {
            Schema::dropIfExists($table_name);
        });

        Schema::enableForeignKeyConstraints();
    }
}
