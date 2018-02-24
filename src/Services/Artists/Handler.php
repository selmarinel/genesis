<?php
/**
 * Created by PhpStorm.
 * User: selma
 * Date: 24.02.2018
 * Time: 11:45
 */

namespace App\Services\Artists;


use App\Repository\ArtistRepository;
use App\Services\Artists\DataTransferObjects\ArtistTracksDTO;
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
        $artist = new ArtistTracksDTO($artistEntity);
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
            $result[] = (new ArtistTracksDTO($artist))();
        }
        return $result;
    }
}