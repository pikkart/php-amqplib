<?php

namespace PhpAmqpLib\Tests\Unit\Helper;

use PhpAmqpLib\Helper\MiscHelper;
use PHPUnit\Framework\MockObject\BadMethodCallException;
use PHPUnit\Framework\TestCase;
use function method_exists;
use function preg_match;

class MiscHelperTest extends TestCase
{
    /**
     * @dataProvider splitSecondsMicrosecondsData
     * @test
     */
    public function split_seconds_microseconds($input, $expected)
    {
        $this->assertEquals($expected, MiscHelper::splitSecondsMicroseconds($input));
    }

    /**
     * @dataProvider hexdumpData
     * @test
     */
    public function hexdump($args, $expected)
    {
        $expr = MiscHelper::hexdump($args[0], $args[1], $args[2], $args[3]);

        if(method_exists($this, 'assertMatchesRegularExpression')) { // phpunit 9
            $this->assertMatchesRegularExpression($expected, $expr);
        } elseif(method_exists($this, 'assertMatchRegExp')) { // phpunit < 9
            $this->assertMatchRegExp($expected, $expr);
        } else { // just for compat
            $this->assertSame(1, preg_match($expected, $expr), "$expr does not match $expected");
        }
    }

    /**
     * @test
     */
    public function method_sig()
    {
        $this->assertEquals('test', MiscHelper::methodSig('test'));
    }

    public function splitSecondsMicrosecondsData()
    {
        return [
            [0, [0, 0]],
            [0.3, [0, 300000]],
            ['0.3', [0, 300000]],
            [3, [3, 0]],
            ['3', [3, 0]],
            [3.0, [3, 0]],
            ['3.0', [3, 0]],
            [3.1, [3, 100000]],
            ['3.1', [3, 100000]],
            [3.123456, [3, 123456]],
            ['3.123456', [3, 123456]],
        ];
    }

    public function hexdumpData()
    {
        return [
            [['FM', false, false, true], '/000\s+46 4d\s+FM/'],
            [['FM', false, true, true], '/000\s+46 4D\s+FM/'],
        ];
    }
}
