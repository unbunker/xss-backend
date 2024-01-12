<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $userText = null;

    #[ORM\Column(type: "text")]
    private ?string $url = null;

    public function __construct(String $userText, String $url)
    {
        $this->userText = $userText;
        $this->url = $url;

    }

    public function getUserText(): ?String
    {
        return $this->userText;
    }

    public function getUrl(): ?String
    {
        return $this->url;
    }
}
