<?php

declare(strict_types=1);

use Entity\Exception\ParameterException;
use Entity\TvShow;

try {
    if (empty($_POST["name"]) || empty($_POST["homepage"]) || empty($_POST["originalName"]) || empty($_POST["overview"])) {
        throw new ParameterException();
    }
    $id = !empty($_POST["id"]) ? (int)$_POST["id"] : null;
    $show = TvShow::create($_POST["name"], $id, $_POST["homepage"], $_POST["originalName"], $_POST["overview"]);
    $show->save();
    header("Location: http://localhost:8000/index.php");
} catch (ParameterException) {
    http_response_code(400);
} catch (Exception) {
    http_response_code(500);
}
