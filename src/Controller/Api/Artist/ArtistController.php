<?php

namespace App\Controller\Api\Artist;


use App\Repository\ArtistRepository;
use App\Services\Artists\Exceptions\ArtistNotFoundException;
use App\Services\Artists\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArtistReadController
 */
class ArtistController extends Controller
{
    /**
     * @param $id
     * @param Handler $handler
     * @Route("/api/artists/{id}", name="artist_read", requirements={"id"="\d+"}, methods={"get"})
     * @return JsonResponse
     */
    public function readOne($id, Handler $handler)
    {
        try {
            $artist = $handler->findArtistById($id);
            return new JsonResponse(['artist' => $artist], Response::HTTP_OK);
        } catch (ArtistNotFoundException $artistNotFoundException) {
            return new JsonResponse(['error' => $artistNotFoundException->getMessage()], $artistNotFoundException->getCode());
        }
    }

    /**
     * @param Request $request
     * @param Handler $handler
     * @return JsonResponse
     * @Route("/api/artists", name="artists_read", methods={"get"})
     */
    public function readCollection(Request $request, Handler $handler)
    {
        $artists = $handler->findArtists($request->query);
        return new JsonResponse(['artists' => $artists], Response::HTTP_OK);
    }
}