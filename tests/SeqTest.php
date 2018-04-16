<?php

namespace Test;

use Heterogeny\Seq;
use PHPUnit\Framework\TestCase;

final class SeqTest extends TestCase
{
    /**
     * @var \Heterogeny\Seq
     */
    protected $seq;

    public function testOffsetSet1(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->seq['haha'] = 10;
    }

    public function testOffsetSet2(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->seq->offsetSet('haha', 10);
    }

    public function testOffsetGet1(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->seq->offsetGet(10);
    }

    public function testOffsetGet2(): void
    {
        $this->assertEquals(4, $this->seq->offsetGet(3));
    }

    public function testOffsetGet3(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->seq['haha'];
    }

    public function testEquals1(): void
    {
        $this->assertTrue($this->seq->equals([1, 2, 3, 4]));
    }

    public function testEquals2(): void
    {
        $this->assertFalse($this->seq->equals([1, 2, 3]));
    }

    public function testEquals3(): void
    {
        /**
         * This is not recommended since all checks will use loose comparison
         */
        $this->assertTrue($this->seq == seq(1, 2, 3, 4));
        $this->assertFalse($this->seq == seq(1, 2, 3));
    }

    public function testEquals4(): void
    {
        $this->assertFalse($this->seq->equals([1, 2, '3', 4]));
    }

    public function testHead(): void
    {
        $this->assertTrue($this->seq->head() === 1);
    }

    public function testLast(): void
    {
        $this->assertTrue($this->seq->last() === 4);
    }

    public function testInit(): void
    {
        $this->assertTrue($this->seq->init()->equals([1, 2, 3]));
    }

    public function testTail(): void
    {
        $this->assertTrue($this->seq->tail()->equals([2, 3, 4]));
    }

    public function testAppend(): void
    {
        $this->assertTrue(
            $this
                ->seq
                ->append(5)
                ->last() === 5
        );
    }

    public function testPrepend(): void
    {
        $this->assertTrue(
            $this
                ->seq
                ->prepend(0)
                ->head() === 0
        );
    }

    public function testConcat1(): void
    {
        $this->assertTrue(
            $this
                ->seq
                ->appendAll(seq(5))
                ->equals([1, 2, 3, 4, 5])
        );
    }

    public function testConcat2(): void
    {
        $this->assertTrue(
            seq(5)
                ->appendAll($this->seq)
                ->equals([5, 1, 2, 3, 4])
        );
    }

    public function testReduce(): void
    {
        $seq = seq("b", "c", "d");
        $reduceLeft = $seq->reduceLeft(function ($acc, $x) {
            return $acc . $x;
        });

        $reduceRight = $seq->reduceRight(function ($acc, $x) {
            return $acc . $x;
        });

        $this->assertTrue($reduceLeft === 'bcd');
        $this->assertTrue($reduceRight === 'dcb');
    }

    public function testFold(): void
    {
        $seq = seq("b", "c", "d");
        $foldLeft = $seq->foldLeft(function ($acc, $x) {
            return $acc . $x;
        }, "a");

        $foldRight = $seq->foldRight(function ($acc, $x) {
            return $acc . $x;
        }, "e");

        $this->assertTrue($foldLeft === 'abcd');
        $this->assertTrue($foldRight === 'edcb');
    }

    public function testZip(): void
    {
        $seq1 = seq("a", "b", "c", "d");
        $seq2 = seq(1, 2, 3, 4);
        $zip1 = $seq1->zip($seq2);
        $zip2 = $seq1->zip($seq2);

        $this->assertTrue($zip1->equals($zip2));

        $zip1 = $seq1->zipLeft($seq2);
        $zip2 = $seq1->zipLeft($seq2);

        $this->assertTrue($zip1->equals($zip2));
    }

    public function testEvery1(): void
    {
        $seq2 = seq(4, 6, 8, 10);

        $this->assertTrue($seq2->every(function ($item) {
            return $item % 2 === 0;
        }));
    }

    public function testEvery2(): void
    {
        $seq2 = seq(4, 6, 8, 9);

        $this->assertFalse($seq2->every(function ($item) {
            return $item % 2 === 0;
        }));
    }

    public function testSome1(): void
    {
        $seq2 = seq(4, 6, 7, 10);

        $this->assertTrue($seq2->some(function ($item) {
            return $item % 2 === 0;
        }));
    }

    public function testSome2(): void
    {
        $seq2 = seq(3, 5, 7, 9);

        $this->assertFalse($seq2->some(function ($item) {
            return $item % 2 === 0;
        }));
    }

    public function testGetOrElse(): void
    {
        $this->assertTrue($this->seq->getOrElse(10) === null);
    }

    public function testUnderlying(): void
    {
        $this->assertTrue($this->seq->all() === [1, 2, 3, 4]);
    }

    public function testMapWithIndex(): void
    {
        $result = $this->seq->mapWithIndex(function ($key, $value) {
            return [$key, $value];
        });

        $this->assertEquals([
            [0, 1],
            [1, 2],
            [2, 3],
            [3, 4],
        ], $result->all());
    }

    protected function setUp(): void
    {
        $this->seq = seq(1, 2, 3, 4);
    }
}
