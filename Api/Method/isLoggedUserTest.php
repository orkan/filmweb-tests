<?php

use Orkan\Filmweb\Api\Method\isLoggedUser;
use PHPUnit\Framework\TestCase;

class isLoggedUserTest extends TestCase
{
	public function testType()
	{
		$this->assertSame( 'get', ( new isLoggedUser )::TYPE );
	}

	public function testFormat()
	{
		$args = array(
		);
		$this->assertSame( 'isLoggedUser', ( new isLoggedUser )->format( $args ) );
	}
}
