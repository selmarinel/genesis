<?php

namespace App\Controller\Api\Artist;

use App\Services\Artists\Exceptions\ArtistNotFoundException;
use App\Services\Artists\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArtistReadController
 */
class ArtistController extends Controller
{

    /** @var RedisAdapter */
    private $cache;

    public function __construct()
    {
        $this->cache = new FilesystemCache();
    }

    /**
     * @param $id
     * @param Handler $handler
     * @return JsonResponse
     * @Route("/api/artists/{id}", name="artist_read", requirements={"id"="\d+"}, methods={"get"})
     */
    public function readOne($id, Handler $handler)
    {
        if ($this->cache->has("artist.$id.result")) {
            $artist = $this->cache->get("artist.$id.result");
            return new JsonResponse(['artist' => $artist], Response::HTTP_OK);
        }

        if (!$this->cache->has("artist.$id.lock")) {
            $this->cache->set("artist.$id.lock", 1, 30);

            try {
                $artist = $handler->findArtistById($id);
                $this->cache->set("artist.$id.result", $artist, 30);
                return new JsonResponse(['artist' => $artist], Response::HTTP_OK);
            } catch (ArtistNotFoundException $artistNotFoundException) {
                $this->cache->delete("artist.$id.result");
                return new JsonResponse(['error' => $artistNotFoundException->getMessage()], $artistNotFoundException->getCode());
            }
        }
        sleep(5);
        return $this->readOne($id, $handler);
    }

    /**
     * @param Request $request
     * @param Handler $handler
     * @return JsonResponse
     * @Route("/api/artists", name="artists_read", methods={"get"})
     */
    public function readCollection(Request $request, Handler $handler)
    {
        $filter = $request->getQueryString();
        $cachedFilter = $this->cache->get("artists.filters");
        $isSeems = $filter === $cachedFilter;

        if ($this->cache->has("artists.filters") && !$isSeems) {
            $this->cache->delete('artists.result');
        }

        if ($this->cache->has("artists.result") && $isSeems) {
            $artists = $this->cache->get("artists.result");
            return new JsonResponse(['artists' => $artists], Response::HTTP_OK);
        }
        if (!$this->cache->has("artists.lock")) {
            $this->cache->set("artists.lock", 1, 10);
            $this->cache->set("artists.filters", $filter, 10);
            $artists = $handler->findArtists($request->query);
            $this->cache->set("artists.result", $artists, 10);
            return new JsonResponse(['artists' => $artists], Response::HTTP_OK);
        }
        sleep(5);
        return $this->readCollection($request, $handler);
    }
}