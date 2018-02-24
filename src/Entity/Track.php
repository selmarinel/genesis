<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrackRepository")
 */
class Track
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column()
     */
    private $name;
    /**
     * @var string
     * @ORM\Column()
     */
    private $link;
    /**
     * @var string
     * @ORM\Column()
     */
    private $cover;

    /**
     * @var Artist
     * @ORM\ManyToOne(targetEntity="Artist", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id")
     */
    private $artist;
}
