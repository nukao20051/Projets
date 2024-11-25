<?php

declare(strict_types=1);

use Entity\Exception\EntityNotFoundException;
use Entity\Season;
use Entity\TvShow;
use Html\AppWebPage;

if (!isset($_GET["seasonId"]) || !ctype_digit($_GET["seasonId"])) {
    header("Location: http://localhost:8000");
    die();
}

$seasonId = $_GET["seasonId"];

$webPage = new AppWebPage();

$webPage->appendCSSUrl("css/episode.css");

try {
    $season = Season::findById((int)$seasonId);
    $tvshow = TvShow::findById($season->getTvShowId());
    $webPage->setTitle("Séries TV : {$tvshow->getName()} {$season->getName()}");

    $nameShow = $tvshow->getName();
    $nameSeason = $season->getName();
    $seasonPosterId = $season->getPosterId();

    $seasonPoster = "<img src='poster.php?posterId=$seasonPosterId' alt='posterShow'/>";
    $webPage->appendContent("<div id='season'><p>$seasonPoster</p><div id='info_season'><a href='tvshow.php?tvshowId={$tvshow->getId()}'><p>$nameShow</p></a><p>$nameSeason</p></div></div>");

    $episodes = $season->getEpisode();

    foreach ($episodes as $episode) {
        $numEpisode = $episode->getEpisodeNumber();
        $nameEpisode = $episode->getName();
        $descEpisode = $episode->getOverview();
        $webPage->appendContent("<div class='info_episode'><p>$numEpisode - $nameEpisode<p>$descEpisode</p></div>");
    }

    $webPage->appendContent("<a id='home' href='index.php'>Retour à la page d'acceuil</a>");

    echo $webPage->toHTML();
} catch(EntityNotFoundException $e) {
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
}
