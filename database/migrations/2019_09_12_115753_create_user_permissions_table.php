<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    protected $table_for;

    public function __construct()
    {
        $this->table_for = config('user_permission.tables');
    }

    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create($this->table_for['roles'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create($this->table_for['permissions'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create($this->table_for['user_roles'], function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('user_id')->references('id')->on($this->table_for['users'])
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on($this->table_for['roles'])
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });

        Schema::create($this->table_for['permission_roles'], function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on($this->table_for['permissions'])
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on($this->table_for['roles'])
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create($this->table_for['permission_model'], function (Blueprint $table) {
            $table->unsignedInteger('permission_id');

            $table->string('model_type');
            $table->unsignedInteger('model_id');
            $table->index(['model_id', 'model_type',], 'model_permissions_model_id_model_type_index');

            $table->foreign('permission_id')
                ->references('id')
                ->on($this->table_for['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'model_id', 'model_type'],
                'model_has_permissions_permission_model_type_primary');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists($this->table_for['roles']);
        Schema::dropIfExists($this->table_for['permissions']);
        Schema::dropIfExists($this->table_for['user_roles']);
        Schema::dropIfExists($this->table_for['permission_roles']);
        Schema::dropIfExists($this->table_for['permission_model']);
        Schema::enableForeignKeyConstraints();
    }
}
