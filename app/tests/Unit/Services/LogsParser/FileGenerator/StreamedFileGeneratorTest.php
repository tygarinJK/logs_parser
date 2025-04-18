<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\LogsParser\FileGenerator;

use App\Services\LogsParser\FileGenerator\NotFoundException;
use App\Services\LogsParser\FileGenerator\StreamedFileGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(StreamedFileGenerator::class)]
final class StreamedFileGeneratorTest extends TestCase
{
    private string $tempFile;

    protected function tearDown(): void
    {
        if (isset($this->tempFile) && file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    public function testFileNotFound(): void
    {
        $this->expectException(NotFoundException::class);

        new StreamedFileGenerator('non_existent_file.log');
    }

    public function testGetLines(): void
    {
        $this->tempFile = $this->createTempFile("line_first\nline_second\nline_third");

        $generator = new StreamedFileGenerator($this->tempFile);
        $lines = $generator->getLines();

        $this->assertIsIterable($lines);
        $this->assertEquals(['line_first', 'line_second', 'line_third'], iterator_to_array($lines));
    }

    public function testRemoveBom(): void
    {
        $this->tempFile = $this->createTempFile("\xEF\xBB\xBFline");

        $generator = new StreamedFileGenerator($this->tempFile);

        $line = current(iterator_to_array($generator->getLines()));

        $this->assertEquals('line', $line);
    }

    private function createTempFile(string $contents): string
    {
        $file = tempnam(sys_get_temp_dir(), 'test_log_');

        if (false === $file) {
            throw new \RuntimeException('Unable to create temp file');
        }

        file_put_contents($file, $contents);

        return $file;
    }
}
