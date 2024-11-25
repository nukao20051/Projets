<?php

declare(strict_types=1);

use Entity\Exception\EntityNotFoundException;
use Entity\Exception\ParameterException;
use Entity\Poster;

try {
    if (!isset($_GET["posterId"]) || empty($_GET["posterId"])) {
        header('Content-Type: image/png');
        header('Location: http://cutrona/but/s2/sae2-01/ressources/public/img/default.png');
        exit();
    }
    if (!ctype_digit($_GET["posterId"])) {
        throw new ParameterException();
    }
    $posterId = $_GET["posterId"];
    $poster = Poster::findById((int)$posterId);
    header('Content-Type: image/jpeg');
    echo $poster->getJpeg();

} catch (ParameterException) {
    http_response_code(400);
} catch (EntityNotFoundException) {
    http_response_code(404);
} catch (Exception) {
    http_response_code(500);
}
