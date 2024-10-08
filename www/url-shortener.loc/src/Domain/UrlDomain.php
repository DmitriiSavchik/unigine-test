<?php

namespace App\Domain;

use App\Entity\Url;
use App\Repository\UrlRepository;
use Doctrine\Persistence\ObjectManager;

class UrlDomain {
    private ObjectManager $entityManager;
    private UrlRepository $urlRepository;

    public function __construct(ObjectManager $entityManager, UrlRepository $urlRepository)
    {
        $this->entityManager = $entityManager;
        $this->urlRepository = $urlRepository;
    }

    public function encodeUrl(string $urlString): string
    {
        $url = $this->urlRepository->findOneByUrl($urlString);

        if ($url && $url->isActive())
            return $url->getHash();

        $url = new Url();
        $url->setUrl($urlString);

        $this->entityManager->persist($url);
        $this->entityManager->flush();

        return $url->getHash();
    }

    public function decodeUrl(string $hash): ?string
    {
        $url = $this->urlRepository->findOneByHash($hash);

        if (!$url || !$url->isActive())
            return null;

        return $url->getUrl();
    }
}