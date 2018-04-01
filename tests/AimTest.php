<?php

namespace Test;

use Heterogeny\JSON;
use Heterogeny\Aim;
use Heterogeny\Scope;
use PHPUnit\Framework\TestCase;

final class AimTest extends TestCase
{
    public function test1(): void
    {
        $sourceDict = dict([
            'a' => 'b',
            'b' => 'c',
            'd' => dict([
                'e' => 'f'
            ]),
            'g' => seq(dict([
                'h' => 'i'
            ]))
        ]);

        $focus0 = Aim::focus('0/a');
        $focus1 = Aim::focus('t');
        $focus2 = Aim::focus('y/z');
        $focus3 = Aim::focus('g/0/h');
        $focus4 = Aim::focus('i/0/j');
        $focus5 = Aim::focus('a/b');
        $focus6 = Aim::focus('a/0');
        $focus8 = Aim::focus('zzz/1');
        $focus9 = Aim::focus('zzz');

        $dict0 = $focus0->set($sourceDict, 1);
        $dict1 = $focus1->set($sourceDict, 2);
        $dict2 = $focus2->set($sourceDict, 3);
        $dict3 = $focus3->set($sourceDict, 4);
        $dict4 = $focus4->set($sourceDict, 5);
        $dict5 = $focus5->set($sourceDict, 6);
        $dict6 = $focus6->set($sourceDict, 7);

        $this->assertEquals(1, $focus0->get($dict0));
        $this->assertEquals(2, $focus1->get($dict1));
        $this->assertEquals(3, $focus2->get($dict2));
        $this->assertEquals(4, $focus3->get($dict3));
        $this->assertEquals(5, $focus4->get($dict4));
        $this->assertEquals(6, $focus5->get($dict5));
        $this->assertEquals(7, $focus6->get($dict6));

        $this->assertTrue($focus0->exists($dict0));
        $this->assertTrue($focus1->exists($dict1));
        $this->assertTrue($focus2->exists($dict2));
        $this->assertTrue($focus3->exists($dict3));
        $this->assertTrue($focus4->exists($dict4));
        $this->assertTrue($focus5->exists($dict5));
        $this->assertTrue($focus6->exists($dict6));
        $this->assertFalse($focus8->exists($dict6));
        $this->assertFalse($focus9->exists($dict6));

        $this->assertTrue(Aim::exists('0/a')($dict0));
        $this->assertTrue(Aim::exists('t')($dict1));
        $this->assertTrue(Aim::exists('y/z')($dict2));
        $this->assertTrue(Aim::exists('g/0/h')($dict3));
        $this->assertTrue(Aim::exists('i/0/j')($dict4));
        $this->assertTrue(Aim::exists('a/b')($dict5));
        $this->assertTrue(Aim::exists('a/0')($dict6));
        $this->assertFalse(Aim::exists('zzz/1')($dict6));
        $this->assertFalse(Aim::exists('zzz')($dict6));

        $dict7 = $focus6->update($dict6, function ($value) {
           return $value * 2;
        });

        $this->assertEquals(14, $focus6->get($dict7));


        $dict = $sourceDict->del('b');

        // check if there was any modifications on the source object
        // modifications should respect immutability principle
        // and also check if 'b' was removed
        $this->assertEquals([
            'a' => 'b',
            'd' => [
                'e' => 'f'
            ],
            'g' => [
                [
                    'h' => 'i'
                ]
            ]
        ], $dict->all());
    }
}
