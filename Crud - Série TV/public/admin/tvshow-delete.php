<?php

declare(strict_types=1);

use Entity\Exception\EntityNotFoundException;
use Entity\Exception\ParameterException;
use Entity\TvShow;

try {
    if (!isset($_GET["tvShowId"]) || !ctype_digit($_GET["tvShowId"])) {
        throw new ParameterException();
    }
    $showId = $_GET["tvShowId"];
    $show = TvShow::findById((int)$showId);
    $show->delete();
    header("Location: http://localhost:8000/index.php");
} catch (ParameterException) {
    http_response_code(400);
} catch (EntityNotFoundException) {
    http_response_code(404);
} catch (Exception) {
    http_response_code(500);
}
