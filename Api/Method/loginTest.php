<?php

use Orkan\Filmweb\Api\Method\login;
use PHPUnit\Framework\TestCase;

class loginTest extends TestCase
{
	private $method;

	protected function setUp(): void
	{
		$this->method = new login;
	}

	public function testType()
	{
		$this->assertSame( 'post', $this->method::TYPE );
	}

	/**
	 * @dataProvider credentialsProvider
	 */
	public function testFormat( $nick, $pass_raw, $pass_prep )
	{
		$args = array(
			login::NICKNAME => $nick,
			login::PASSWORD => $pass_raw,
		);
		$this->assertSame( 'login ["' . $nick . '", "' . $pass_prep . '", 1]', $this->method->format( $args ) );
	}

	public function credentialsProvider()
	{
		return array (
			//      | name | pass raw      | pass prepared
			array ( 'user' , 'pass'        , 'pass'          ),
			array ( 'adam1', ')ErWZ&%u"CE9', ')ErWZ&%u\"CE9' ), // addslashes()
			array ( 'MisiU', "Z*cq+a'XmRb^", "Z*cq+a\'XmRb^" ), // addslashes()
		);
	}
}
