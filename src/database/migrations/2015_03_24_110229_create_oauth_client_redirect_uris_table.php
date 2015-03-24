<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthClientRedirectUrisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('oauth_client_redirect_uris', function(Blueprint $table) {
            $table->increments('id');
            $table->string('client_id');
            $table->string('redirect_uri');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('oauth_client_redirect_uris');
	}

}
