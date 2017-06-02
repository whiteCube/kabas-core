<?php

namespace Kabas\Config;

use Kabas\App;

class LanguageRepository
{
    protected $available = [];

    public function __construct(array $available)
    {
        foreach ($available as $locale => $args) {
            $locale = is_numeric($locale) ? $args : $locale;
            $args = is_array($args) ? $args : [];
            $this->register($locale, $args);
        }
    }

    /**
    * Adds given locale to repository and returns language instance
    * @param string $locale
    * @param array $args
    * @return Kabas\Config\Language
    */
    public function register(string $locale, array $args = [])
    {
        if($this->find($locale)) return false;
        $language = new Language($locale, $args);
        if(!$language->locale) return false;
        $this->available[] = $language;
        return $language;
    }

    /**
    * Determines if given locale is an available lang
    * @param string $locale
    * @return boolean
    */
    public function is($locale)
    {
        if($this->find($locale)) return true;
        return false;
    }

    /**
    * Returns all languages as defined in config
    * @return array
    */
    public function getAll()
    {
        return $this->available;
    }

    /**
    * Returns current language
    * @return \Kabas\Config\Language
    */
    public function getCurrent()
    {
        foreach ($this->available as $language) {
            if($language->isCurrent) return $language;
        }
    }

    /**
    * Returns default language as defined in config
    * @return \Kabas\Config\Language
    */
    public function getDefault()
    {
        return $this->find(App::config()->get('lang.default'));
    }

    /**
    * Returns a language based on given locale or slug
    * @param string $locale
    * @return \Kabas\Config\Language
    */
    public function find($locale)
    {
        foreach ($this->available as $language) {
            if($language->locale->toW3C() == $locale || $language->slug == $locale) return $language;
        }
    }

    /**
    * Returns requested language or default based on mixed argument
    * @param mixed $locale
    * @return \Kabas\Config\Language
    */
    public function getOrDefault($locale)
    {
        if(is_a($locale, Language::class)) return $locale;
        if($this->is($locale)) return $this->find($locale);
        return $this->getDefault();
    }

    /**
    * Sets given locale as current used language
    * @return \Kabas\Config\Language
    */
    public function set($locale)
    {
        foreach ($this->available as $language) {
            $language->isCurrent = false;
        }
        if(!($language = $this->getOrDefault($locale))) return;
        $language->activate();
        return $language;
    }
}
