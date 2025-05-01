<?php

namespace Html;

class AppWebPage extends WebPage
{
    public function __construct(string $title = "")
    {
        parent::__construct($title);
        parent::appendCSSUrl('/css/style.css');
    }
    public function toHtml(): string
    {
        $lastModified = parent::getLastModification();
        $html = <<<HTML
        <!doctype html>
        <html lang='fr'>
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">{$this->getHead()}
                <title>{$this->getTitle()}</title>
            </head>
            <body>
                <header class="header">
                    <h1>{$this->getTitle()}</h1>
                </header>
                <main class="content"> 
                    <div class="list">
                        {$this->getBody()}
                    </div>
                </main>
                <footer class="footer">
                    <div id="lastmodified">{$lastModified}</div>
                </footer>
            </body>
        </html>
        HTML;
        return $html;
    }
}
