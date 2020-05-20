<?php
use Orkan\Filmweb\Api\Method\isLoggedUser;
use PHPUnit\Framework\TestCase;

class isLoggedUserTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new isLoggedUser() )::TYPE );
	}

	public function test_format()
	{
		/* @formatter:off */
		$args = array(
		);
		/* @formatter:on */
		$this->assertSame( 'isLoggedUser', ( new isLoggedUser() )->format( $args ) );
	}
}
