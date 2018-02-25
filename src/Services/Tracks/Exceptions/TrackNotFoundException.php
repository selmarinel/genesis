<?php

namespace App\Services\Tracks\Exceptions;


use Symfony\Component\HttpFoundation\Response;

class TrackNotFoundException extends TrackServiceException
{
    public $code = Response::HTTP_NOT_FOUND;
}