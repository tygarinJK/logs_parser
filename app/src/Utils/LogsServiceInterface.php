<?php

namespace App\Utils;

use Doctrine\DBAL\Exception;

interface LogsServiceInterface
{
    /**
     * Returns the number of log records in the database.
     *
     * @param array $parameters
     *   An array of parameters to filter the log records. The following keys are supported:
     *   - serviceNames: An array of service names to filter by.
     *   - statusCode: A status code to filter by.
     *   - startDate: A start date to filter by (in 'Y-m-d H:i:s' format).
     *   - endDate: An end date to filter by (in 'Y-m-d H:i:s' format).
     *
     * @return int
     *   The number of log records that match the given parameters.
     *
     * @throws Exception
     */
    public function countLogRecords(array $parameters): int;
}