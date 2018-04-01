<?php

namespace Test;

use Heterogeny\Seq;
use PHPUnit\Framework\TestCase;

final class DictTest extends TestCase
{
    /**
     * @var \Heterogeny\Dict
     */
    protected $dict;

    public function testOffsetSetAndGet(): void
    {
        $this->dict['a'] = ':(';
        $this->assertEquals(':(', $this->dict['a']);
        $this->assertEquals('i', $this->dict->get('g/0/h'));
        $this->assertEquals(null, $this->dict->getOrElse('zzz', null));
    }

    public function testOutOfBounds1(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->dict->get('b/0/0/t');
    }

    public function testOutOfBounds2(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->dict->get('r/0/0/t');
    }

    public function testMerge(): void
    {
        $dict1 = dict(['a' => 'b']);
        $dict2 = dict(['c' => 'd']);
        $dict3 = $dict1->merge($dict2);

        $this->assertTrue($dict3->equals(dict([
            'a' => 'b',
            'c' => 'd'
        ])));
    }
    
    protected function setUp(): void
    {
        $this->dict = dict([
            'a' => 'b',
            'b' => 'c',
            'd' => dict([
                'e' => 'f'
            ]),
            'g' => seq(dict([
                'h' => 'i'
            ]))
        ]);
    }
}
