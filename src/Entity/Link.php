<?php

namespace App\Entity;

use App\Repository\LinkRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\UrlExist;

/**
 * @ORM\Entity(repositoryClass=LinkRepository::class)
 * @UniqueEntity(fields="short_link", message="This short link exist.")
 */
class Link
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2048)
     * @Assert\Sequentially({
     *     @Assert\Url,
     *     @UrlExist
     * })
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=9, unique=true)
     */
    private $short_link;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getShortLink(): ?string
    {
        return $this->short_link;
    }

    public function setShortLink(string $short_link): self
    {
        $this->short_link = $short_link;

        return $this;
    }
}
