<?php

use Orkan\Filmweb\Api\Method\isLoggedUser;
use Orkan\Filmweb\Api\Method\login;
use PHPUnit\Framework\TestCase;

/**
 * @covers Orkan\Filmweb\Api\Method\Method
 * @uses Orkan\Filmweb\Api\Method\isLoggedUser
 * @uses Orkan\Filmweb\Api\Method\login
 */
class MethodTest extends TestCase
{
	public function testGetType()
	{
		//$this->markTestIncomplete('This test has not been implemented yet.');

		$this->assertSame( 'post', ( new login )->getType() );
		$this->assertSame( 'get' , ( new isLoggedUser )->getType() );
	}

	public function testToString()
	{
		$this->assertSame( 'login', (string) new login );
	}
}
