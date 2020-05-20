<?php
use Orkan\Filmweb\Api\Method\isLoggedUser;
use Orkan\Filmweb\Api\Method\login;
use PHPUnit\Framework\TestCase;

class MethodTest extends TestCase
{

	public function test_getType_()
	{
		$this->assertSame( 'post', ( new login() )->getType() );
		$this->assertSame( 'get', ( new isLoggedUser() )->getType() );
	}

	public function test_toString_()
	{
		$this->assertSame( 'login', (string) new login() );
	}
}
