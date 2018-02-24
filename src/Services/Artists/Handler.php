<?php

namespace App\Services\Artists;


use App\Repository\ArtistRepository;
use App\Services\DataTransferObjects\ArtistDTO;
use App\Services\Artists\Exceptions\ArtistNotFoundException;
use Symfony\Component\HttpFoundation\ParameterBag;

class Handler
{
    const TAKE_ARTISTS = 20;
    /**
     * @var ArtistRepository
     */
    private $artistRepository;

    public function __construct(ArtistRepository $artistRepository)
    {
        $this->artistRepository = $artistRepository;
    }

    /**
     * @param $id
     * @return array
     * @throws ArtistNotFoundException
     */
    public function findArtistById($id): array
    {
        $artistEntity = $this->artistRepository->find($id);
        if (!$artistEntity) {
            throw new ArtistNotFoundException;
        }
        $artist = new ArtistDTO($artistEntity);
        return $artist();
    }

    /**
     * @param ParameterBag|null $parameterBag
     * @return array
     */
    public function findArtists(ParameterBag $parameterBag = null): array
    {
        $search = $parameterBag->get('search');
        $take = ($parameterBag->get('take')) ?: static::TAKE_ARTISTS;
        $offset = ($parameterBag->get('offset')) ?: 0;
        $artists = $this->artistRepository->search($search, $offset, $take);
        $result = [];
        /** @var  $artist */
        foreach ($artists as $artist) {
            $result[] = (new ArtistDTO($artist))();
        }
        return $result;
    }
}