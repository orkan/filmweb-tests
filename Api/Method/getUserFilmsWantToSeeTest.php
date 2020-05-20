<?php
use Orkan\Filmweb\Api\Method\getUserFilmsWantToSee;
use PHPUnit\Framework\TestCase;

class getUserFilmsWantToSeeTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getUserFilmsWantToSee() )::TYPE );
	}

	public function test_format( $userId = 36797 )
	{
		/* @formatter:off */
		$args = array(
			getUserFilmsWantToSee::USERID => $userId,
		);
		/* @formatter:on */
		$this->assertSame( "getUserFilmsWantToSee [$userId, 1]", ( new getUserFilmsWantToSee() )->format( $args ) );
	}
}
