<?php

namespace App\Controller;

use App\Entity\UrlStats;
use App\Repository\UrlStatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class UrlStatsController extends AbstractController
{
    private $entityManager;
    private $urlStatsRepository;

    public function __construct(EntityManagerInterface $entityManager, UrlStatsRepository $urlStatsRepository)
    {
        $this->entityManager = $entityManager;
        $this->urlStatsRepository = $urlStatsRepository;
    }

    /**
     * @Route("/add-url", methods={"POST"})
     */
    public function addUrl(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $url = $data['url'] ?? null;
        $createdDate = $data['createdDate'] ?? null;

        if (!$url || !$createdDate) {
            return new JsonResponse(['error' => 'Invalid data provided'], 400);
        }

        $urlStats = new UrlStats();
        $urlStats->setUrl($url);
        $urlStats->setCreatedDate(new \DateTimeImmutable($createdDate));

        $this->entityManager->persist($urlStats);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'URL added successfully'], 201);
    }

    /**
     * @Route("/stats/unique-urls", methods={"GET"})
     */
    public function getUniqueUrls(Request $request): JsonResponse
    {
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');

        if (!$startDate || !$endDate) {
            return new JsonResponse(['error' => 'Please provide start_date and end_date'], 400);
        }

        $start = new \DateTimeImmutable($startDate);
        $end = new \DateTimeImmutable($endDate);

        $uniqueUrls = $this->urlStatsRepository->countUniqueUrlsBetween($start, $end);

        return new JsonResponse(['unique_urls' => $uniqueUrls], 200);
    }

    /**
     * @Route("/stats/unique-urls-by-domain", methods={"GET"})
     */
    public function getUniqueUrlsByDomain(Request $request): JsonResponse
    {
        $domain = $request->query->get('domain');
        if (!$domain) {
            return new JsonResponse(['error' => 'Please provide a domain'], 400);
        }

        $uniqueUrlsByDomain = $this->urlStatsRepository->countUniqueUrlsByDomain($domain);

        return new JsonResponse(['unique_urls_by_domain' => $uniqueUrlsByDomain], 200);
    }
}
