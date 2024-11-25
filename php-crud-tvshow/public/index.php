<?php

declare(strict_types=1);

use Entity\Collection\GenreCollection;
use Entity\Collection\TvShowCollection;
use Entity\TvShow;
use Html\AppWebPage;

$webPage = new AppWebPage();

$webPage->appendCSSUrl("css/index.css");

$webPage->setTitle("Séries TV");

$genres = GenreCollection::findAll();

$webPage->appendContent("<div class='filter'>");

$webPage->appendContent("<form action='index.php' method='get'><button type='submit'>Tous</button></form>");

foreach ($genres as $genre) {
    $webPage->appendContent("<form action='index.php'><button type='submit' name='genreId' value='{$genre->getId()}'>{$genre->getName()}</button></form>");
}

$webPage->appendContent("</div>");
$webPage->appendContent("<div class='menu'>
    <form method='post' action='admin/tvshow-form.php'>
    <button type='submit'>Ajouter une série</button>
    </form>
    </div>");

if (!$_GET) {
    $shows = TvShowCollection::findAll();

    foreach ($shows as $show) {
        $nomShow = $show->getName();
        $posterId = !empty($show->getPosterId()) ? $show->getPosterId() : null;
        $desc = $show->getOverview();
        $poster = "<img src='poster.php?posterId=$posterId' alt='image'/>";
        $webPage->appendContent("<a href='tvshow.php?tvshowId={$show->getId()}' class='serie'><p>$poster</p><div class='info_serie'><p>$nomShow</p><p>$desc</p></div></a>");
    }
} else {
    $shows = TvShow::findByGenreId((int)$_GET['genreId']);

    foreach ($shows as $show) {
        $nomShow = $show->getName();
        $posterId = !empty($show->getPosterId()) ? $show->getPosterId() : null;
        $desc = $show->getOverview();
        $poster = "<img src='poster.php?posterId=$posterId' alt='image'/>";
        $webPage->appendContent("<a href='tvshow.php?tvshowId={$show->getId()}' class='serie'><p>$poster</p><div class='info_serie'><p>$nomShow</p><p>$desc</p></div></a>");
    }
}

echo $webPage->toHTML();
