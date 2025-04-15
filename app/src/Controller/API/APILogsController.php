<?php

namespace App\Controller\API;

use App\Utils\LogsServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class APILogsController extends AbstractAPIController
{
    public function __construct(
        private readonly LogsServiceInterface $logsService,
    )
    {
    }

    #[Route('/count', name: 'api.count_logs', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $parameters = $request->query->all();

        $output = $this->logsService->countLogRecords($parameters);

        return new JsonResponse(['counter' => $output], Response::HTTP_OK);
    }
}