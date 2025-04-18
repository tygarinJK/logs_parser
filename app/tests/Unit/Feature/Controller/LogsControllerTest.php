<?php

declare(strict_types=1);

namespace App\Tests\Unit\Feature\Controller;

use App\Controller\LogsController;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[CoversClass(LogsController::class)]
final class LogsControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = self::createClient();
        $client->request('GET', '/count');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }
}
