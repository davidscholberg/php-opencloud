<?php

/**
 * Unit Tests
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Common;

use OpenCloud\Common\Base;
use OpenCloud\Common\Lang;

/**
 * Can't test Base directly, since it is an abstract class, so we instantiate it
 */
class MyBase extends Base
{

    public $foo;
    protected $bar;
    private $baz;
    private $metadata;
    
    public function setBar($bar)
    {
        $this->bar = $bar . '!!!';
    }
    
    public function getBar()
    {
        return $this->bar;
    }
}

class BaseTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $my;

    public function __construct()
    {
        $this->my = new MyBase;
    }

    public function test_gettext()
    {
        $this->assertEquals(Lang::translate('Hello'), 'Hello');
    }

    public function test_Incorrect_Method()
    {
        $this->assertNull($this->my->fooBarMethod());
    }
    
    public function test_Setting_NonExistent_Property()
    {
        $object = $this->my;
        
        $object->getLogger()
            ->setOption('outputToFile', false)
            ->setEnabled(true);
        
        $object->setGhost('foobar');
        
        $this->expectOutputRegex('#property has not been defined.#');
    }
    
    public function test_noslash()
    {
        $this->assertEquals(Lang::noslash('String/'), 'String');
        $this->assertEquals(Lang::noslash('String'), 'String');
    }

    public function testDebug()
    {
        $logger = $this->my->getLogger();
        $logger->setEnabled(true);
        
        $logger->info("HELLO, WORLD!");
        $this->expectOutputRegex('/ELLO/');
    }
    
    public function test_Metadata_Populate()
    {
        $object = $this->my;
        $data = (object) array(
            'metadata' => array(
                'foo' => 'bar'
            )
        );
        $object->populate($data);
        
        $this->assertInstanceOf('OpenCloud\Common\Metadata', $object->getMetadata());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\URLError
     */
    public function testUrl()
    {
        $this->my->Url();
    }

    public function testSetProperty()
    {
        $this->my->setBar('hello');
        $this->assertEquals('hello!!!', $this->my->getBar());
        
        $this->my->setBaz('goodbye');
        $this->assertEquals('goodbye', $this->my->getBaz());
    }

}