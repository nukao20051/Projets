<?php

declare(strict_types=1);

use Entity\Exception\EntityNotFoundException;
use Entity\TvShow;
use Html\AppWebPage;

if (!isset($_GET["tvshowId"]) || !ctype_digit($_GET["tvshowId"])) {
    header("Location: http://localhost:8000");
    die();
}

$tvshowId = $_GET["tvshowId"];

$webPage = new AppWebPage();

$webPage->appendCSSUrl("css/tvshow.css");

try {
    $tvshow = TvShow::findById((int)$tvshowId);
    $webPage->setTitle("Séries TV : {$tvshow->getName()}");

    $webPage->appendContent("<elements class='menu'>
    <form method='POST' action='admin/tvshow-form.php?tvShowId=$tvshowId'>
    <button type='submit'>Modifier</button>
    <p></p>
    </form>
    <form method='POST' action='admin/tvshow-delete.php?tvShowId=$tvshowId'>
    <button type='submit'>Supprimer</button>
    </form>
    </elements>");

    $nameShow = $tvshow->getName();
    $originalNameShow = $tvshow->getOriginalName();
    $showPosterId = !empty($tvshow->getPosterId()) ? $tvshow->getPosterId() : null;
    $desc = $tvshow->getOverview();
    $showposter = "<img src='poster.php?posterId=$showPosterId' alt='posterShow'/>";
    $webPage->appendContent("<div id='serie'><p>$showposter</p><div id='info_serie'><p class='name'>$nameShow</p><p class='name'>(Nom original : $originalNameShow)</p><p>$desc</p></div></div>");

    $seasons = $tvshow->getSeason();

    foreach ($seasons as $season) {
        $posterId = !empty($season->getPosterId()) ? $season->getPosterId() : null;
        $nameSeason = $season->getName();
        $webPage->appendContent("<a class='season' href='episode.php?seasonId={$season->getId()}'><img src='poster.php?posterId=$posterId' alt='posterSeason'/><p class='info_season'>$nameSeason</p></a>");
    }

    $webPage->appendContent("<a id='home' href='index.php'>Retour à la page d'acceuil</a>");

    echo $webPage->toHTML();
} catch(EntityNotFoundException $e) {
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
}
