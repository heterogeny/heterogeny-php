<?php

namespace Test;

use Heterogeny\JSON;
use PHPUnit\Framework\TestCase;

final class JsonTest extends TestCase
{
    protected $json1;

    public function testDecode1(): void
    {
        $result = JSON::decode($this->json1)->dict();
        $this->assertTrue($result->get('a/b')->equals(seq('c')));

        self::assertEquals(1, $result->get('a/d/e'), 'Could not access a/d/e');
        self::assertEquals("c", $result->get('a/b/0'), 'Could not access a/b/0');
        self::assertEquals(1, $result->get('a/f/0/a'), 'Could not access a/f/0/a');
    }

    public function testEncode1(): void
    {
        $result = JSON::encode(dict());

        self::assertEquals('{}', $result);
    }

    public function testEncode2(): void
    {
        $result = JSON::encode(seq());

        self::assertEquals('[]', $result);
    }

    protected function setUp(): void
    {
        $this->json1 = /** @lang JSON */
            <<<JSON
{
    "a": {
        "b": ["c"],
        "d": {
            "e": 1
        },
        "f": [{"a": 1}]
    }
}
JSON;

    }
}
