<?php

/**
 * ThejournalApiTest class
 */
class ThejournalApiTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Data Provider. Includes the home page, 'google' and 'facebook' tags
	 *
	 * @dataProvider addDataProvider
	 */
	public function addDataProvider() {
		return [
			array('/'),
			array('google'),
			array('facebook'),
		];
	}

	/**
	 * @covers ExecuteHomePage()
	 *
	 * @dataProvider addDataProvider
	 */
	public function testExecuteHomePage($request) {
		$ThejournalApi = new ThejournalApi;
		$result = $ThejournalApi->Execute($request);
		$this->assertNotEmpty($result);
		foreach ($result as $article) {
			$this->assertArrayHasKey('title', $article);
			$this->assertArrayHasKey('excerpt', $article);
			$this->assertArrayHasKey('images', $article);
			$this->assertArrayHasKey('type', $article);
		}
	}
}
