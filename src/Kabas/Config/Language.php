<?php

namespace Kabas\Config;

use Kabas\App;
use Kabas\Utils\Lang;
use WhiteCube\Lingua\Service as Lingua;
use Kabas\Http\Request\Locale;

class Language
{
    public $original;
    public $slug;
    public $label;
    public $native;
    public $php;
    public $locale;
    public $isCurrent = false;
    public $isDefault = false;

    public function __construct(string $locale, array $args, $isDefault = false)
    {
        $this->original = $locale;
        $this->locale = $this->parseLocale($locale);
        if(!$this->locale) return;
        $this->slug = $args['slug'] ?? $this->extractSlug();
        $this->label = $args['label'] ?? $this->extractLabel();
        $this->native = $args['native'] ?? $this->extractNative();
        $this->php = $args['php'] ?? $this->locale->toPHP();
        $this->isDefault = $isDefault;
    }

    /**
    * Defines this locale as currently active
    * @return void
    */
    public function activate()
    {
        $this->isCurrent = true;
        setlocale(LC_ALL, $this->php . '.utf8', $this->php . '.UTF-8', $this->php);
        // Keep integer and float values with a "dot" decimal separator.
        // Numbers should be formatted using number_format().
        setlocale(LC_NUMERIC, 'C');
        // Remember the choice for the next requests
        setcookie(Locale::COOKIE_NAME, $this->slug, time()+60*60*24*365);
    }

    /**
    * Tries to instantiate a Lingua object for given string
    * @param string $locale
    * @return WhiteCube\Lingua\Service
    */
    protected function parseLocale($locale)
    {
        try {
            $language = Lingua::createFromW3c($locale);
        } catch (\Exception $e) {
            return false;
        }
        return $language;
    }

    /**
    * Finds automatic slug string for current locale
    * @return string
    */
    protected function extractSlug()
    {
        return $this->tryFormats([
            'ISO_639_1',
            'ISO_639_3',
            'ISO_639_2t',
            'ISO_639_2b',
            'W3C'
        ]);
    }

    /**
    * Finds automatic label string for current locale
    * @return string
    */
    protected function extractLabel()
    {
        return ucfirst($this->tryFormats([
            'name',
            'native',
            'ISO_639_1',
            'ISO_639_3',
            'ISO_639_2t',
            'ISO_639_2b'
        ]));
    }

    /**
    * Finds automatic native (autonym) string for current locale
    * @return string
    */
    protected function extractNative()
    {
        return ucfirst($this->tryFormats([
            'native',
            'name',
            'ISO_639_1',
            'ISO_639_3',
            'ISO_639_2t',
            'ISO_639_2b'
        ]));
    }

    /**
    * Finds first filled string for given formats
    * @param array $formats
    * @return string
    * @codeCoverageIgnore
    */
    protected function tryFormats(array $formats)
    {
        foreach ($formats as $format) {
            $slug = call_user_func([$this->locale, 'to' . $format]);
            if(strlen($slug)) return $slug;
        }
    }
}
