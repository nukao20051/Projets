<?php

namespace Html;

class WebPage
{
    private string $head = "";
    private string $title = "";
    private string $body = "";

    /**
     * Constructeur d'objets, permet l'instanciation (du paramètre
     * title)
     *
     * @param string $title
     */
    public function __construct(string $title = "")
    {
        $this->title = $title;
    }

    /**
     * Accesseur de l'attribut d'instance head
     *
     * @return string
     */
    public function getHead(): string
    {
        return $this->head;
    }

    /**
     * Accesseur de l'attribut d'instance title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Accesseur de l'attribut d'instance body
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Setter de l'attribut d'instance title
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Permet d'ajouter un contenu quelconque à l'attribut d'instance
     * body
     *
     * @param string $content
     * @return void
     */
    public function appendContent(string $content): void
    {
        $this->body .= $content;
    }

    /**
     * Permet d'ajouter du contenu CSS à l'attribut d'instance head
     *
     * @param string $css
     * @return void
     */
    public function appendCSS(string $css): void
    {
        $this->head .= "<style>$css</style>";
    }

    /**
     * Permet d'ajouter un url CSS à l'attribut d'instance head
     *
     * @param string $url
     * @return void
     */
    public function appendCSSUrl(string $url): void
    {
        $this->head .= "<link href='$url' rel='stylesheet'>";
        #Ajouter un rel !
    }

    /**
     * Permet d'ajouter du contenu JavaScript à l'attribut d'instance head
     *
     * @param string $js
     * @return void
     */
    public function appendJs(string $js): void
    {
        $this->head .= "<script> $js </script>";
    }

    /**
     * Permet d'ajouter un url JavaScript à l'attribut d'instance head
     *
     * @param string $url
     * @return void
     */
    public function appendJsUrl(string $url): void
    {
        $this->head .= "<script src='$url'></script>";
    }

    /**
     * Permet d'ajouter un contenu quelconque à l'attribut d'instance
     * head
     *
     * @param string $content
     * @return void
     */
    public function appendToHead(string $content): void
    {
        $this->head .= $content;
    }

    /**
     * Permet d'obtenir la dernière date à laquelle le fichier HTML a
     * été modifié
     *
     * @return string
     */
    public static function getLastModification(): string
    {
        return date('\D\e\r\n\i\è\r\e \m\o\d\i\f\i\c\a\t\i\o\n \d\e \c\e\t\t\e \p\a\g\e \l\e j/m/Y \à H:m:s', getlastmod());
        #static quand la méthode ne dépend d'aucun attribut instance
        #dans les exercices : attribut souligné pour static
    }

    /**
     * Utilise les attributs d'instance de la classe pour créer un
     * HTML contenu dans une chaîne de caractères
     *
     * @return string
     */
    public function toHTML(): string
    {
        $lastModified = self::getLastModification();
        #car méthode statique
        $html = <<<HTML
        <!doctype html>
        <html lang='fr'>
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">{$this->getHead()}
                <title>{$this->getTitle()}</title>
            </head>
            <body>{$this->getBody()}
            <div id="lastmodified">{$lastModified}</div>
            </body>
        </html>
        HTML;
        return $html;
        #charset très important ici ! Le réutiliser tout le temps
        #name aussi, ATTENTION : name et charset pas dans la même balise
    }
}
