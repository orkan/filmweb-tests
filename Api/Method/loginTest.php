<?php
use Orkan\Filmweb\Api\Method\login;
use PHPUnit\Framework\TestCase;

class loginTest extends TestCase
{
	private $method;

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function credentialsProvider()
	{
		/* @formatter:off */
		return array (
			//                        | name | pass          | addslashes(pass)
			'ascii'        => array ( 'user' , 'pass'        , 'pass'          ),
			'single quote' => array ( 'MisiU', "Z*cq+a'XmRb^", "Z*cq+a\'XmRb^" ),
			'double quote' => array ( 'adam1', ')ErWZ&%u"CE9', ')ErWZ&%u\"CE9' ),
		);
		/* @formatter:on */
	}

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests:
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function test_type()
	{
		$this->assertSame( 'post', ( new login() )::TYPE );
	}

	/**
	 *
	 * @dataProvider credentialsProvider
	 */
	public function test_format( $nick, $pass_raw, $pass_prep )
	{
		/* @formatter:off */
		$args = array(
			login::NICKNAME => $nick,
			login::PASSWORD => $pass_raw,
		);
		/* @formatter:on */
		$this->assertSame( 'login ["' . $nick . '", "' . $pass_prep . '", 1]', ( new login() )->format( $args ) );
	}
}
