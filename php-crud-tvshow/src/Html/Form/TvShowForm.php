<?php

namespace Html\Form;

use Entity\TvShow;
use Html\StringEscaper;

class TvShowForm
{
    use StringEscaper;
    private ?TvShow $tvshow;

    public function __construct(?TvShow $tvshow = null)
    {
        $this->tvshow = $tvshow;
    }
    public function getTvshow(): ?TvShow
    {
        return $this->tvshow;
    }
    public function getHtmlForm(string $action): string
    {
        $html = <<<HTML
            <form method="POST" action="$action">
                <input type="hidden" name="id" value="{$this->tvshow?->getId()}" />
                <label>Name
                    <input type="text" name="name" value="{$this->escapeString($this->tvshow?->getName())}" required>
                </label><p>
                <label>Original Name
                    <input type="text" name="originalName" value="{$this->escapeString($this->tvshow?->getOriginalName())}" required>
                </label><p>
                <label>Homepage
                    <input type="text" name="homepage" value="{$this->escapeString($this->tvshow?->getHomepage())}" required>
                </label><p>
                <label>Overview
                    <input type="text" name="overview" value="{$this->escapeString($this->tvshow?->getOverview())}" required>
                </label><p>
                <button type="submit">Enregistrer</button>
            </form>
        HTML;
        return $html;
    }
}
