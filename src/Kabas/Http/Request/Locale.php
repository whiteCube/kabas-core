<?php

namespace Kabas\Http\Request;

use Kabas\Http\Request\Query;
use Kabas\Config\LanguageRepository;
use Kabas\Config\Language;

class Locale
{
    /**
     * The application's available LanguageRepository
     * @var Kabas\Config\LanguageRepository
     */
    private $locales;

    /**
     * The request query.
     * @var Kabas\Http\Request\Query
     */
    private $query;

    /**
     * The locale found in this request
     * @var Kabas\Config\Language
     */
    protected $locale;

    /**
     * Location where current locale was defined
     * @var string
     */
    protected $source;

    /**
     * The Locale Cookie Name
     * @var string
     */
    const COOKIE_NAME = 'kabas-locale';

    public function __construct(LanguageRepository $locales, Query $query)
    {
        $this->locales = $locales;
        $this->query = $query;
        $this->defineFromSourcesPriority(['query', 'cookie', 'browser', 'config']);
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function shouldRedirect()
    {
        return ($this->query->getLocale() !== $this->getLocale()->slug);
    }

    protected function defineFromSourcesPriority(array $sources)
    {
        foreach ($sources as $source) {
            if(is_null($locale = $this->getLanguageFromSource($source))) continue;
            $this->current = $locale;
            $this->source = $source;
            break;
        }
    }

    protected function getLanguageFromSource($source)
    {
        $method = $this->getSourceMethodFromName($source);
        if(!($locale = call_user_func([$this, $method]))) return;
        if(is_string($locale) && !($locale = $this->locales->find($locale))) return;
        return $locale;
    }

    protected function getSourceMethodFromName($source)
    {
        return 'getLocaleFrom' . ucfirst($source);
    }

    public function getLocaleFromQuery()
    {
        return $this->query->getLocale();
    }

    public function getLocaleFromCookie()
    {
        if(isset($_COOKIE[self::COOKIE_NAME])) {
            return $_COOKIE[self::COOKIE_NAME];
        }
    }

    public function getLocaleFromBrowser()
    {
        if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) return;
        if(!strlen($_SERVER['HTTP_ACCEPT_LANGUAGE'])) return;
        foreach ($this->getHttpAcceptLanguagePreferencesArray() as $locale) {
            if($locale = $this->locales->find($locale)) return $locale;
        }
    }

    public function getLocaleFromConfig()
    {
        return $this->locales->getDefault();
    }

    protected function getHttpAcceptLanguagePreferencesArray()
    {
        $languages = [];
        foreach (explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $preference) {
            $preference = $this->getHttpAcceptLanguagePreferenceDefinition($preference);

            // Language preference was not parsable
            if(!$preference) continue;

            // Wildcard language tag is not precise enough
            if($preference['locale'] === '*') continue;

            // Browser thinks the q-factor of the language is not good enough
            if($preference['quality'] <= 0.5) continue;

            // Language could be acceptable
            $key = $this->getHttpAcceptLanguagePreferenceKeyForQuality($preference['quality'], array_keys($languages));
            $languages[$key] = $preference['locale'];

            // Return no more than 3 preference languages in order to avoid
            // user misconfiguration frustrations
            if(count($languages) >= 3) break;
        }
        ksort($languages);
        return $languages;
    }

    protected function getHttpAcceptLanguagePreferenceDefinition($preference)
    {
        $definition = [];
        $preference = explode(';', $preference);
        if(!isset($preference[0]) || !strlen($definition['locale'] = trim($preference[0]))) {
            return;
        }
        $definition['quality'] = $this->getHttpAccpetLanguagePreferenceQuality($preference[1] ?? null);
        return $definition;
    }

    protected function getHttpAccpetLanguagePreferenceQuality($quality)
    {
        if(is_null($quality)) return 1;
        if(!strlen($quality = trim($quality))) return 0;
        if(!strpos($quality, 'q=') !== 0) return 0;
        if(!strlen($quality = trim(substr($quality, 2)))) return 0;
        return floatval($quality);
    }

    protected function getHttpAcceptLanguagePreferenceKeyForQuality($quality, $existing)
    {
        $key = 100 - intval(($quality > 1 ? 1 : $quality) * 100);
        do {
            $key++;
        } while (in_array($key, $existing));
        return $key;
    }

}
