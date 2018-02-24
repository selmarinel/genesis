<?php

namespace App\Services\Artists\DataTransferObjects;


use App\Entity\Artist;

/**
 * @property Artist entity
 */
class ArtistTracksDTO
{
    /**
     * @var Artist
     */
    private $entity;

    /**
     * ArtistTracksDTO constructor.
     * @param Artist $artist
     */
    public function __construct(Artist $artist)
    {
        $this->entity = $artist;
    }

    /**
     * @return string
     */
    public function getArtistName(): string
    {
        return $this->entity->getName();
    }

    /**
     * @return string
     */
    public function getArtistDescription(): string
    {
        return $this->entity->getDescription();
    }

    public function getTracks(): array
    {
        return $this->entity->getTracks();
    }

    /**
     * @return int
     */
    public function getArtistId(): int
    {
        return $this->entity->getId();
    }

    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'id' => $this->getArtistId(),
            'name' => $this->getArtistName(),
            'description' => $this->getArtistDescription(),
            'tracks' => $this->getTracks()
        ];
    }
}