<?php

namespace App\Entity;

use App\Repository\UrlRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UrlRepository::class)
 */
class Url
{
    private int $lifetimeInSeconds = 5;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=14)
     */
    private $hash;

    /**
     * @ORM\Column(name="created_date", type="datetime_immutable")
     */
    private $createdDate;

    /**
     * @ORM\Column(name="expiration_date", type="datetime_immutable", nullable=true)
     */
    private $expirationDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sent = false;

    public function __construct()
    {
        $date = new \DateTimeImmutable();
        $this->setCreatedDate($date);
        $this->setExpirationDate($date->add(new \DateInterval('PT2M')));
        $this->setHash($date->format('YmdHis'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeImmutable
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeImmutable $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeImmutable
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTimeImmutable $expirationDate): self
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }
    public function getSent(): ?bool
    {
        return $this->sent;
    }

    public function setSent(bool $sent): self
    {
        $this->sent = $sent;

        return $this;
    }
    public function isActive(): bool
    {
        $now = new \DateTimeImmutable();

        $diff = $now->getTimestamp() - $this->getCreatedDate()->getTimestamp();

        return $diff < $this->lifetimeInSeconds;
    }
}
