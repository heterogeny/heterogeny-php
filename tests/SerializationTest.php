<?php

namespace Test;

use Heterogeny\Dict;
use Heterogeny\Seq;
use PHPUnit\Framework\TestCase;

final class SerializationTest extends TestCase
{
    protected $json1;

    public function testSerialize(): void
    {
        $dict = new Dict(["a" => "b", "c" => "d", "e" => new Dict(["f" => "g", "h" => "i"])]);

        $serializedDict = serialize($dict);

        /** @var Dict $deserializedDict */
        $deserializedDict = unserialize($serializedDict);

        $this->assertInstanceOf(Dict::class, $deserializedDict);

        $this->assertTrue($deserializedDict->equals($dict));

        $seq = new Seq([1, 2, 3, 4, 5]);

        $serializedSeq = serialize($seq);

        /** @var Seq $deserializedSeq */
        $deserializedSeq = unserialize($serializedSeq);

        $this->assertInstanceOf(Seq::class, $deserializedSeq);

        $this->assertTrue($deserializedSeq->equals($seq));
    }

    public function testVarExport(): void
    {
        $dict = new Dict(["a" => "b", "c" => "d", "e" => new Dict(["f" => "g", "h" => "i"])]);

        $exportedDict = var_export($dict, true);

        /** @var Dict $importedDic */
        $importedDict = eval("return " . $exportedDict . ";");

        $this->assertInstanceOf(Dict::class, $importedDict);
        $this->assertTrue($importedDict->equals($dict));
    }
}
