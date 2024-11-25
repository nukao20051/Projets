<?php

namespace Html;

trait StringEscaper
{
    /**
     * Permet de transformer des caractères spéciaux d'une chaine de
     * caractère en caractères normaux
     *
     * @param string $string
     * @return string
     */
    public function escapeString(?string $text): ?string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5);   #Ex: transformer <body> sans prendre en compte < >
        #Transformer en HTML5 pour ce caractère spécial '
    }
    public function stripTagsAndTrim(?string $text): ?string
    {
        $res = strip_tags($text);
        $res = trim($res);
        return $res;
    }
}
