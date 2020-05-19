<?php

use Orkan\Filmweb\Api\Method\getUserFilmsWantToSee;
use PHPUnit\Framework\TestCase;

class getUserFilmsWantToSeeTest extends TestCase
{
	public function testType()
	{
		$this->assertSame( 'get', ( new getUserFilmsWantToSee )::TYPE );
	}
	
	public function testFormat( $userId = 36797 )
	{
		$args = array(
			getUserFilmsWantToSee::USERID => $userId,
		);
		$this->assertSame( "getUserFilmsWantToSee [$userId, 1]", ( new getUserFilmsWantToSee )->format( $args ) );
	}
}
