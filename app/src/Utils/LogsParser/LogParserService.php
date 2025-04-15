<?php

namespace App\Utils\LogsParser;

use PDO;

class LogParserService implements LogParserServiceInterface
{
    private PDO $pdo;

    public function __construct(string $dsn, string $user, string $pass)
    {
        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    public function processLogRecord(string $record): bool
    {
        $parsed = $this->parseLogLine($record);

        if (!$parsed) {
            return false;
        }

        [$serviceName, $dateStr, $statusCode, $content] = $parsed;

        $date = \DateTime::createFromFormat('d/M/Y:H:i:s O', $dateStr);
        if (!$date) {
            return false;
        }

        $stmt = $this->pdo->prepare('
            INSERT INTO log_messages (service_name, status_code, date, content)
            VALUES (:service, :status, :date, :content)
        ');

        $stmt->execute([
            'service' => $serviceName,
            'status'  => $statusCode,
            'date'    => $date->format('Y-m-d H:i:s'),
            'content' => $content,
        ]);

        return true;
    }

    public function parseLogLine(string $line): ?array
    {
        $pattern = '/^(\S+)\s+-\s+-\s+\[(.*?)\]\s+"(.*?)"\s+(\d{3})$/';

        if (preg_match($pattern, $line, $matches)) {
            return [
                $matches[1], // service_name
                $matches[2], // date
                $matches[4], // status
                $matches[3], // content
            ];
        }

        return null;
    }
}