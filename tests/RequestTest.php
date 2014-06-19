<?php

namespace Icambridge\Tests\Http;

use Icambridge\Http\Request;

class RequestTest extends TestCase
{
    /** @test */
    public function expectsContinueShouldBeFalseByDefault()
    {
        $headers = array();
        $request = new Request('GET', '/', array(), '1.1', $headers);

        $this->assertFalse($request->expectsContinue());
    }

    /** @test */
    public function expectsContinueShouldBeTrueIfContinueExpected()
    {
        $headers = array('Expect' => '100-continue');
        $request = new Request('GET', '/', array(), '1.1', $headers);

        $this->assertTrue($request->expectsContinue());
    }
}
