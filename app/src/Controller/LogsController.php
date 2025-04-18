<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\LogsQueryData;
use App\Repository\LogEntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class LogsController extends AbstractController
{
    #[Route('/count', name: 'app.logs.count', methods: ['GET'])]
    public function countAction(Request $request, LogEntryRepository $repository): JsonResponse
    {
        $parameters = $request->query->all();
        $output = $repository->getCount(LogsQueryData::fromArray($parameters));

        return new JsonResponse(['counter' => $output]);
    }
}
