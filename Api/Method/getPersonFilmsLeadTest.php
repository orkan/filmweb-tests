<?php
use Orkan\Filmweb\Api\Method\getPersonFilmsLead;
use PHPUnit\Framework\TestCase;

class getPersonFilmsLeadTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getPersonFilmsLead() )::TYPE );
	}

	public function test_format( $id = 36797 )
	{
		/* @formatter:off */
		$args = array(
			getPersonFilmsLead::ID => $id,
		);
		/* @formatter:on */

		$cfg = ( new getPersonFilmsLead() )->getDefaults( [] );

		$expected = sprintf( 'getPersonFilmsLead [%u, %u]', $args[getPersonFilmsLead::ID], $cfg['limit'] );
		$result = ( new getPersonFilmsLead() )->format( $args );

		$this->assertSame( $expected, $result );
	}
}
