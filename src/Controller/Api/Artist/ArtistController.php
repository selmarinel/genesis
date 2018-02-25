<?php

namespace App\Controller\Api\Artist;

use App\Services\Artists\Exceptions\ArtistNotFoundException;
use App\Services\Artists\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Caching\Handler as CachingService;

/**
 * Class ArtistReadController
 */
class ArtistController extends Controller
{
    /**
     * @param $id
     * @param Handler $handler
     * @param CachingService $cachingHandler
     * @return JsonResponse
     * @Route("/api/artists/{id}", name="artist_read", requirements={"id"="\d+"}, methods={"get"})
     */
    public function readOne($id, Handler $handler, CachingService $cachingHandler)
    {
        try {
            $artist = $cachingHandler->getFromCacheByKey("artist.$id", function () use ($handler, $id) {
                return $handler->findArtistById($id);
            });
            return new JsonResponse(['artist' => $artist], Response::HTTP_OK);
        } catch (ArtistNotFoundException $artistNotFoundException) {
            $cachingHandler->removeKeyFromCache("artist.$id");
            return new JsonResponse(['error' => $artistNotFoundException->getMessage()], $artistNotFoundException->getCode());
        }
    }

    /**
     * @param Request $request
     * @param Handler $handler
     * @param CachingService $cachingHandler
     * @return JsonResponse
     * @Route("/api/artists", name="artists_read", methods={"get"})
     */
    public function readCollection(Request $request, Handler $handler, CachingService $cachingHandler)
    {
        $artists = $cachingHandler->getFromCacheByFilters("artists",
            $request->getQueryString(),
            function () use ($handler, $request) {
                return $handler->findArtists($request->query);
            });
        return new JsonResponse(['artists' => $artists], Response::HTTP_OK);
    }
}