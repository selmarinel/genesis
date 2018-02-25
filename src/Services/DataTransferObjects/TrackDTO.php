<?php

namespace App\Services\DataTransferObjects;


use App\Entity\Track;

class TrackDTO
{
    /** @var Track */
    private $entity;

    /**
     * @param Track $track
     */
    public function __construct(Track $track)
    {
        $this->entity = $track;
    }

    public function getTrackIdentification(): int
    {
        return $this->entity->getId();
    }

    /**
     * @return string
     */
    public function getTrackName(): string
    {
        return $this->entity->getName();
    }

    /**
     * @return string
     */
    public function getTrackCover(): string
    {
        return $this->entity->getCover();
    }

    /**
     * @return string
     */
    public function getTrackLink(): string
    {
        return $this->entity->getLink();
    }

    public function getTrackArtist()
    {
        $artist = $this->entity->getArtist();
        return [
            'artist_id' => $artist->getId(),
            'artist_name' => $artist->getName(),
        ];
    }

    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'id' => $this->getTrackIdentification(),
            'name' => $this->getTrackName(),
            'cover' => $this->getTrackCover(),
            'link' => $this->getTrackLink()
        ];
    }

    public function getWithArtist()
    {
        return $this() + $this->getTrackArtist();
    }
}