<?php

namespace App\Services\Artists\Exceptions;


use Symfony\Component\HttpFoundation\Response;

class ArtistNotFoundException extends ArtistServiceException
{
    public $code = Response::HTTP_NOT_FOUND;
}