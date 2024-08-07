<?php

use PHPUnit\Framework\TestCase;
use Editiel98\Kernel\PathGenerator;

class PathGeneratorTest extends TestCase
{
    public function testPathGenerator(): void
    {
        $arrayPath = [
            'path',
            'to',
            'the',
            'file'
        ];
        $expected = 'path' . DIRECTORY_SEPARATOR . 'to' . DIRECTORY_SEPARATOR . 'the' . DIRECTORY_SEPARATOR . 'file';
        $this->assertEquals($expected, PathGenerator::generatePath($arrayPath));
    }

    public function testPathGeneratorWithRelative(): void
    {
        $arrayPath = [
            'path',
            'to',
            '..',
            'the',
            'file'
        ];
        $expected = 'path' . DIRECTORY_SEPARATOR . 'the' . DIRECTORY_SEPARATOR  . 'file';
        $this->assertEquals($expected, PathGenerator::generatePath($arrayPath));
    }

    public function testPathGeneratorWithAbsolute(): void
    {
        $arrayPath = [
            __DIR__,
            '..',
            'to',
            'the',
            'file'
        ];
        $position = strrpos(__DIR__, DIRECTORY_SEPARATOR);
        $truncated = substr(__DIR__, 0, $position);
        $expected = $truncated . DIRECTORY_SEPARATOR . 'to' . DIRECTORY_SEPARATOR . 'the' . DIRECTORY_SEPARATOR . 'file';
        $this->assertEquals($expected, PathGenerator::generatePath($arrayPath));
    }
}
