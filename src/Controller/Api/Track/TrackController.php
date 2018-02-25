<?php

namespace App\Controller\Api\Track;

use App\Services\Tracks\Exceptions\TrackNotFoundException;
use App\Services\Tracks\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Services\Caching\Handler as CachingService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrackController extends Controller
{
    /**
     * @param $id
     * @param Handler $handler
     * @param CachingService $cachingHandler
     * @return JsonResponse
     *
     * @Route("/api/tracks/{id}", name="track_read", requirements={"id"="\d+"}, methods={"get"})
     */
    public function readOne($id, Handler $handler, CachingService $cachingHandler)
    {
        try {
            $track = $cachingHandler->getFromCacheByKey("track.$id", function () use ($handler, $id) {
                return $handler->findTrackById($id);
            });
            return new JsonResponse(['track' => $track], Response::HTTP_OK);
        } catch (TrackNotFoundException $trackNotFoundException) {
            $cachingHandler->removeKeyFromCache("track.$id");
            return new JsonResponse(['error' => $trackNotFoundException->getMessage()], $trackNotFoundException->getCode());
        }
    }

    /**
     * @param Request $request
     * @param Handler $handler
     * @param CachingService $cachingHandler
     * @return JsonResponse
     * @Route("/api/tracks", name="tracks_read", methods={"get"})
     */
    public function readCollection(Request $request, Handler $handler, CachingService $cachingHandler)
    {
        $tracks = $cachingHandler->getFromCacheByFilters("tracks",
            $request->getQueryString(),
            function () use ($handler, $request) {
                return $handler->findTracks($request->query);
            });
        return new JsonResponse(['tracks' => $tracks], Response::HTTP_OK);
    }
}