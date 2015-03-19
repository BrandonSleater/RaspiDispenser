<?php

class HomeTest extends TestCase {

	/**
	 * Trying to view root url.
	 *
	 * @return void
	 */
	public function testAccessToRoot()
	{
		$response = $this->call('GET', '/');
		$this->assertResponseOk();
	}

	/**
	 * Trying to view home url while logged in.
	 *
	 * @return void
	 */
	public function testAccessToHomeSuccess()
	{
		Auth::loginUsingId(1);

		$response = $this->call('GET', '/home');
		$view = $response->original;

		$this->assertResponseOk();

		// Scheduling Datatable
		$this->assertViewHas('table');
		$table = $view['table'];

		var_dump($table);
		exit;

		$this->assertInstanceOf('Chumper\Datatable\Datatable', $table);

		$this->assertViewHas('status');
	}


	/**
	 * Trying to view home url while not logged in.
	 *
	 * @return void
	 */
	public function testAccessToHomeFailed()
	{
		$response = $this->call('GET', '/home');
		$this->assertResponseStatus(302);
	}

}
