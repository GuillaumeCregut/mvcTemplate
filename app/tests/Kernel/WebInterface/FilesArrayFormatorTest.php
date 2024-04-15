<?php

use PHPUnit\Framework\TestCase;
use Editiel98\Kernel\WebInterface\FilesArrayFormator;

class FilesArrayFormatorTest extends TestCase
{
    public function testFilesArrayFormatorWithSingle(): void
    {
        $files = [
            'fichiers' => [
                'name' => 'file1.png',
                'full_path' => 'fullpath1',
                'type' => 'image/png',
                'tmp_name' => 'tmppath/tofile1',
                'error' => 0,
                'size' => 10025
            ]
        ];

        $expected = [
            'fichiers' => [
                [
                    'name' => 'file1.png',
                    'full_path' => 'fullpath1',
                    'type' => 'image/png',
                    'tmp_name' => 'tmppath/tofile1',
                    'error' => 0,
                    'size' => 10025
                ]
            ]
        ];
        $this->assertEquals($expected, FilesArrayFormator::convert($files));
    }

    public function testFilesArrayFormatorWithMultiples(): void
    {
        $files = [
            'fichiers' => [
                'name' => [
                    'file1.png',
                    'file2.jpg'
                ],
                'full_path' => [
                    'fullpath1',
                    'fullpath2'
                ],
                'type' => [
                    'image/png',
                    'image/jpeg'
                ],
                'tmp_name' => [
                    'tmppath/tofile1',
                    'tmppath/tofile2'
                ],
                'error' => [
                    0,
                    0
                ],
                'size' => [
                    10025,
                    2548
                ]
            ]
        ];

        $expected = [
            'fichiers' => [
                [
                    'name' => 'file1.png',
                    'full_path' => 'fullpath1',
                    'type' => 'image/png',
                    'tmp_name' => 'tmppath/tofile1',
                    'error' => 0,
                    'size' => 10025
                ],
                [
                    'name' => 'file2.jpg',
                    'full_path' => 'fullpath2',
                    'type' => 'image/jpeg',
                    'tmp_name' => 'tmppath/tofile2',
                    'error' => 0,
                    'size' => 2548
                ]
            ]
        ];
        $this->assertEquals($expected, FilesArrayFormator::convert($files));
    }
}
