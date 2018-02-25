<?php

namespace App\Services\Tracks;


use App\Entity\Track;
use App\Repository\TrackRepository;
use App\Services\DataTransferObjects\TrackDTO;
use App\Services\Tracks\Exceptions\TrackNotFoundException;
use Symfony\Component\HttpFoundation\ParameterBag;

class Handler
{
    const TAKE_TRACKS = 20;

    /** @var TrackRepository */
    private $trackRepository;

    public function __construct(TrackRepository $trackRepository)
    {
        $this->trackRepository = $trackRepository;
    }

    /**
     * @param $id
     * @return array
     * @throws TrackNotFoundException
     */
    public function findTrackById($id): array
    {
        $track = $this->trackRepository->find($id);
        if (!$track) {
            throw new TrackNotFoundException;
        }
        return (new TrackDTO($track))->getWithArtist();

    }

    public function findTracks(ParameterBag $parameterBag = null): array
    {
        $search = $parameterBag->get('search');
        $take = ($parameterBag->get('take')) ?: static::TAKE_TRACKS;
        $offset = ($parameterBag->get('offset')) ?: 0;

        $tracks = $this->trackRepository->search($search, $take, $offset);
        $result = [];
        /** @var Track $track */
        foreach ($tracks as $track) {
            $result[] = (new TrackDTO($track))->getWithArtist();
        }
        return $result;
    }
}