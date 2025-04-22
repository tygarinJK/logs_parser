<?php

declare(strict_types=1);

namespace App\Tests\Feature\Controller;

use App\Controller\LogsController;
use App\Repository\LogEntryRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\MockObject\Exception;

/**
 * @internal
 */
#[CoversClass(LogsController::class)]
final class LogsControllerTest extends WebTestCase
{
    /**
     * @return iterable<string, array{array<string, mixed>, int}>
     *
     * @throws RandomException
     */
    public static function requestScenarios(): iterable
    {
        yield 'only serviceNames' => [
            ['serviceNames' => ['serviceName']],
            random_int(1, 9999),
        ];


    }

    /**
     * @param array<string, mixed> $queryParams
     *
     * @throws \JsonException
     * @throws Exception
     */
    #[DataProvider('requestScenarios')]
    public function testIndex(array $queryParams, int $expectedCount): void
    {
        $client = self::createClient();
        $container = self::getContainer();

        $logRepository = $this->createMock(LogEntryRepositoryInterface::class);
        $logRepository->method('getCount')->willReturn($expectedCount);
        $container->set(LogEntryRepositoryInterface::class, $logRepository);

        $client->request('GET', '/count?'.http_build_query($queryParams));

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode(), 'Expected successful response');
        $this->assertJson($response->getContent());

        $expectedJson = json_encode(['counter' => $expectedCount], JSON_THROW_ON_ERROR);

        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent(), 'Unexpected JSON response');
    }
}
