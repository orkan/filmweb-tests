<?php
use Orkan\Filmweb\Api\Method\getFilmPersonsLead;
use PHPUnit\Framework\TestCase;

class getFilmPersonsLeadTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getFilmPersonsLead() )::TYPE );
	}

	public function test_format( $id = 36797 )
	{
		/* @formatter:off */
		$args = array(
			getFilmPersonsLead::ID => $id,
		);
		/* @formatter:on */

		$cfg = ( new getFilmPersonsLead() )->getDefaults( [] );

		$expected = sprintf( 'getFilmPersonsLead [%u, %u]', $args[getFilmPersonsLead::ID], $cfg['limit'] );
		$result = ( new getFilmPersonsLead() )->format( $args );

		$this->assertSame( $expected, $result );
	}
}
