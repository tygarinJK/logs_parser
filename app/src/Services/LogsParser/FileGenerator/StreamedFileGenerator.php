<?php

declare(strict_types=1);

namespace App\Services\LogsParser\FileGenerator;

/**
 * Reads log files line-by-line using streaming.
 * Removes BOM (Byte Order Mark) from the first line if present.
 */
class StreamedFileGenerator implements FileGeneratorInterface
{
    private \SplFileObject $file;

    public function __construct(
        private readonly string $path
    ) {
        if (!is_file($this->path)) {
            throw new NotFoundException(sprintf('File does not exist: "%s"', $this->path));
        }

        $this->file = new \SplFileObject($this->path);

        $this->file->setFlags(
            \SplFileObject::READ_AHEAD
            | \SplFileObject::SKIP_EMPTY
            | \SplFileObject::DROP_NEW_LINE
        );
    }

    public function getLines(): iterable
    {
        foreach ($this->file as $index => $line) {
            yield 0 === $index
                ? $this->removeBom($line)
                : $line;
        }
    }

    private function removeBom(string $line): string
    {
        return str_starts_with($line, "\xEF\xBB\xBF")
            ? substr($line, 3)
            : $line;
    }
}
