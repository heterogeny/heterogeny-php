<?php

namespace Test;

use Heterogeny\Seq;
use Heterogeny\Tuple;
use Heterogeny\Dict;
use PHPUnit\Framework\TestCase;

final class EqualableTest extends TestCase
{
    protected $seq;

    public function testEquals(): void
    {
        $seq1 = new Seq([
            new Seq([
                new Seq([
                    new Seq([
                        new Seq([
                            new Tuple([1, 2]),
                            new Tuple([3, 4]),
                            new Tuple([5, 6]),
                            new Tuple([7, 8]),
                        ]),
                        //new Dict([
                        //    'a' => 'b'
                        //])
                    ])
                ])
            ])
        ]);

        $seq2 = new Seq([
            new Seq([
                new Seq([
                    new Seq([
                        new Seq([
                            new Tuple([1, 2]),
                            new Tuple([3, 4]),
                            new Tuple([5, 6]),
                            new Tuple([7, 8]),
                        ]),
                        //new Dict([
                        //    'a' => 'b'
                        //])
                    ])
                ])
            ])
        ]);

        $seq3 = new Seq([
            new Seq([
                new Seq([
                    new Seq([
                        new Seq([
                            new Tuple([1, 2]),
                            new Tuple([3, 4]),
                            new Tuple([5, 6]),
                            new Tuple([9, 10]),
                        ])
                    ])
                ])
            ])
        ]);

        $this->assertTrue($seq1->equals($seq2));
        $this->assertTrue($seq1 == $seq2);
        $this->assertFalse($seq1->equals($seq3));
    }
}
