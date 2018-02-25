<?php

namespace App\DataFixtures;


use App\Entity\Artist;
use App\Entity\Track;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArtistsFixture extends Fixture
{

    /**
     * @return \Generator
     */
    private function getGeneratorValues(): \Generator
    {
        yield ['name' => 'Ill Niño', 'description' => 'Ill Niño', 'tracks' => [
            [
                'name' => 'The Alibi of Tyrants',
                'cover' => 'https://lh5.ggpht.com/Ptpq5EgEqxR8_xRAd9u09BC0UGpxHFpbu0n7jsK-F5y6UlGFo25GhtUQE2MgAKv98YPE8p0Pbw=s90-c-e100',
                'link' => 'https://play.google.com/music/m/T4mszdas5kgr7flixiivzw7qmpm?t=The_Alibi_of_Tyrants_-_Ill_Nino'
            ],
            [
                'name' => 'How Can I Live',
                'cover' => 'https://lh4.ggpht.com/zkc4-Hygy4b_hLkTazaNognpR-Svp2GsReArz8zEMaJespeLfgZlXFMZwTHg5zwaKJxx4L1l7A=s90-c-e100',
                'link' => 'https://play.google.com/music/m/Tyeb7pqcxju6xjhadxguuijhldq?t=How_Can_I_Live_-_Ill_Nino'
            ]
        ]];
        yield ['name' => 'Akiyuki Morimoto', 'description' => 'Akiyuki Morimoto', 'tracks' => [
            [
                'name' => 'Back for More',
                'cover' => 'https://lh3.googleusercontent.com/u0ReYUtgO8Rq9eEhp-L4-vpeqVgCpRVgsYMRUeL6jGgvwyQpLAc-2JD1NQOAV0EUxaNFbmVa=s90-c-e100',
                'link' => 'https://play.google.com/music/m/Tq6iafy53yc3kxw34qi2i4qreyi?t=Back_for_More_-_Akiyuki_Morimoto'

            ], [
                'name' => 'Love to Death',
                'cover' => 'https://lh3.googleusercontent.com/u0ReYUtgO8Rq9eEhp-L4-vpeqVgCpRVgsYMRUeL6jGgvwyQpLAc-2JD1NQOAV0EUxaNFbmVa=s90-c-e100',
                'link' => 'https://play.google.com/music/m/Tiryxniht4f2m2qznx66v25cy7q?t=Love_to_Death_-_Akiyuki_Morimoto'
            ], [
                'name' => 'White Room',
                'cover' => 'https://lh3.googleusercontent.com/u0ReYUtgO8Rq9eEhp-L4-vpeqVgCpRVgsYMRUeL6jGgvwyQpLAc-2JD1NQOAV0EUxaNFbmVa=s90-c-e100',
                'link' => 'https://play.google.com/music/m/Tifjjtkxeasd442whw2r55233cu?t=White_Room_-_Akiyuki_Morimoto'
            ]
        ]];
        yield ['name' => 'Biosystem55', 'description' => 'Biosystem55', 'tracks' => [
            [
                'name' => 'Anymore',
                'cover' => 'https://lh3.ggpht.com/KPOQqMlu9AZMuT7MMs-uCO3w4xgiAx8cnvgrHtiU182IiIsP4fSIFPAdKVjc_4sGnQRffV4sLA=s90-c-e100',
                'link' => 'https://play.google.com/music/m/Tuxqciirumn4oslwfw2rzxofdmi?t=Anymore_-_Biosystem55'
            ]
        ]];
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var array $generatorValue */
        foreach ($this->getGeneratorValues() as $generatorValue) {
            $artist = new Artist();
            $artist->setName($generatorValue['name']);
            $artist->setDescription($generatorValue['description']);
            foreach ($generatorValue['tracks'] as $generatorTrack) {
                $track = new Track();
                $track->setName($generatorTrack['name']);
                $track->setLink($generatorTrack['link']);
                $track->setCover(isset($generatorTrack['cover']) ? $generatorTrack['cover'] : '');
                $track->setArtist($artist);
                $artist->addTrack($track);
            }
            $manager->persist($artist);
        }

        $manager->flush();
    }
}