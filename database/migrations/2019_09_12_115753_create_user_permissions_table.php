<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class CreateUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create($this->table('roles'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create($this->table('permissions'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create($this->table('user_roles'), function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->primary(['user_id', 'role_id'])->unique();
        });

        Schema::create($this->table('permission_roles'), function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on($this->table('permissions'))
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on($this->table('roles'))
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create($this->table('permission_model'), function (Blueprint $table) {
            $table->unsignedInteger('permission_id');

            $table->string('model_type');
            $table->unsignedInteger('model_id');
            $table->index(['model_id', 'model_type',], 'model_permissions_model_id_model_type_index');

            $table->foreign('permission_id')
                ->references('id')
                ->on($this->table('permissions'))
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

        $this->tables()->each(function ($table_name) {
            Schema::dropIfExists($table_name);
        });

        Schema::enableForeignKeyConstraints();
    }

    private function table($key): string
    {
        return Config::get('user_permission.tables.' . $key);
    }

    private function tables(): Collection
    {
        $tables = Config::get('user_permission.tables', []);

        return Collection::make($tables)->filter(function ($value, $key) {
            return $key !== 'users';
        });
    }
}
