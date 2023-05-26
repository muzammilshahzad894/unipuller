<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPagesettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagesettings', function (Blueprint $table) {
            $table->string('contact_email_BD')->nullable()->after('contact_email');
            $table->string('street_BD')->nullable()->after('street');
            $table->string('phone_BD')->nullable()->after('phone');
            $table->string('fax_BD')->nullable()->after('fax');
            $table->string('email_BD')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagesettings', function (Blueprint $table) {
            $table->dropColumn(['contact_email_BD','street_BD','phone_BD','fax_email_BD','email_BD']);

        });
    }
}
