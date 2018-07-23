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

    /**
     * Returns the current locale
     * @return Kabas\Config\Language
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Returns the source that was used to define
     * the current locale
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Checks if the current locale is well written
     * in the query. If not, a 301 redirect should be performed.
     * @return bool
     */
    public function shouldRedirect()
    {
        return ($this->query->getLocale() !== $this->getLocale()->slug);
    }

    /**
     * Tries the given sources in order of priority in order
     * to determine the current locale. Once found it will set
     * it into the application's LanguageRepository.
     * @param array $sources
     * @return void
     */
    protected function defineFromSourcesPriority(array $sources)
    {
        foreach ($sources as $source) {
            if(is_null($locale = $this->getLanguageFromSource($source))) continue;
            $this->current = $locale;
            $this->source = $source;
            break;
        }
        $this->locales->set($this->current);
    }

    /**
     * Runs one probable source in order to determine
     * if it has a defined locale.
     * @param string $source
     * @return Kabas\Config\Language|null
     */
    protected function getLanguageFromSource($source)
    {
        $method = $this->getSourceMethodFromName($source);
        if(!($locale = call_user_func([$this, $method]))) return;
        if(is_string($locale) && !($locale = $this->locales->find($locale))) return;
        return $locale;
    }

    /**
     * Formats a given source into its testing method name.
     * @param string $source
     * @return string
     */
    protected function getSourceMethodFromName($source)
    {
        return 'getLocaleFrom' . ucfirst($source);
    }

    /**
     * Runs the query source method.
     * @return string|null
     */
    public function getLocaleFromQuery()
    {
        return $this->query->getLocale();
    }

    /**
     * Runs the cookie source method.
     * @return string|null
     */
    public function getLocaleFromCookie()
    {
        if(isset($_COOKIE[self::COOKIE_NAME])) {
            return $_COOKIE[self::COOKIE_NAME];
        }
    }

    /**
     * Runs the HTTP_ACCEPT_LANGUAGE header source method.
     * @return Kabas\Config\Language|null
     */
    public function getLocaleFromBrowser()
    {
        if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) return;
        if(!strlen($_SERVER['HTTP_ACCEPT_LANGUAGE'])) return;
        foreach ($this->getHttpAcceptLanguagePreferencesArray() as $locale) {
            if($locale = $this->locales->find($locale)) return $locale;
        }
    }

    /**
     * Runs the last (always defined) configuration source method.
     * @return Kabas\Config\Language|null
     */
    public function getLocaleFromConfig()
    {
        return $this->locales->getDefault();
    }

    /**
     * Parses the HTTP_ACCEPT_LANGUAGE header in order to extract
     * its 3 best locale codes.
     * @return array
     */
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

    /**
     * Transforms a HTTP_ACCEPT_LANGUAGE header locale definition into
     * a definition array.
     * @param string $preference
     * @return array|null
     */
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

    /**
     * Transforms a HTTP_ACCEPT_LANGUAGE quality-factor into a usable
     * float number.
     * @param ?string $quality
     * @return float
     */
    protected function getHttpAccpetLanguagePreferenceQuality($quality)
    {
        if(is_null($quality)) return 1;
        if(!strlen($quality = trim($quality))) return 0;
        if(!strpos($quality, 'q=') !== 0) return 0;
        if(!strlen($quality = trim(substr($quality, 2)))) return 0;
        return floatval($quality);
    }

    /**
     * Transforms a quality-factor float number into a
     * priority key integer.
     * @param float $quality
     * @param array $existing
     * @return int
     */
    protected function getHttpAcceptLanguagePreferenceKeyForQuality($quality, $existing)
    {
        $key = 100 - intval(($quality > 1 ? 1 : $quality) * 100);
        do {
            $key++;
        } while (in_array($key, $existing));
        return $key;
    }

}
