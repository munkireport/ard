<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class ArdRewriteColumns extends Migration
{
    private $tableName = 'ard';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->boolean('console_allows_remote')->nullable();
            $table->boolean('load_menu_extra')->nullable();
            $table->boolean('screensharing_request_permission')->nullable();
            $table->boolean('vnc_enabled')->nullable();
            $table->boolean('allow_all_local_users')->nullable();
            $table->boolean('directory_login')->nullable();
            $table->mediumText('admin_machines')->nullable();
            $table->mediumText('administrators')->nullable();
            $table->mediumText('task_servers')->nullable();
            
            $table->index('console_allows_remote');
            $table->index('load_menu_extra');
            $table->index('screensharing_request_permission');
            $table->index('vnc_enabled');
            $table->index('allow_all_local_users');
            $table->index('directory_login');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('console_allows_remote');
            $table->dropColumn('load_menu_extra');
            $table->dropColumn('screensharing_request_permission');
            $table->dropColumn('vnc_enabled');
            $table->dropColumn('allow_all_local_users');
            $table->dropColumn('directory_login');
            $table->dropColumn('admin_machines');
            $table->dropColumn('administrators');
            $table->dropColumn('task_servers');         
        });
    }
}
