<?php

namespace Test;

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

    public function testFilter(): void
    {
        $result = $this->dict->filter(function ($key, $item) {
            return in_array($key, ['a', 'd']);
        });

        $this->assertEquals(['a', 'd'], $result->keys());
    }

    public function testContains(): void
    {
        $result1 = $this->dict->contains('a');
        $result2 = $this->dict->contains('d/e');
        $result3 = $this->dict->contains('d/z');

        $this->assertTrue($result1);
        $this->assertTrue($result2);
        $this->assertFalse($result3);
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
