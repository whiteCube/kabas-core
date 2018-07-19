<?php 

namespace Kabas\Cmd\Commands;

use Kabas\Utils\File;
use Kabas\Config\Settings;
use Kabas\Config\LanguageRepository;

class Content extends Command
{
    
    protected $settings;
    protected $languages;

    public function __construct()
    {
        $this->settings = new Settings();
        $this->languages = new LanguageRepository($this->settings->pluck('lang.available'), $this->settings->get('lang.default'));
    }

    public function page($page = null, ...$langs)
    {
        return $this->makeContent($page, 'page', 'templates', $langs);
    }

    public function partial($partial = null, ...$langs)
    {
        return $this->makeContent($partial, 'partial', 'partials', $langs);
    }

    public function menu($menu = null, ...$langs)
    {
        return $this->makeContent($menu, 'menu', 'menus', $langs);
    }

    public function object($object = null, ...$langs)
    {
        return $this->makeContent($object, 'object', 'models', $langs);
    }

    /**
     * Make a content file for a content-type
     * @param  string $type
     * @param  string $structure
     * @return void
     */
    protected function makeContent($name, $type, $structure, $langs)
    {
        echo 'Kabas: making content for ' . $type . ' "' . $name . '"';
        foreach($this->checkLangs($langs) as $lang) {
            $fields = $this->fetchFields($structure, $name);
            if($type == 'object') $this->generateObjectFile($name, $lang, $fields);
            else $this->generateContentFile($name, $type . 's', $lang, $fields);
        }
        echo "\nDone!";
    }

    /**
     * Checks that specified languages exist in application.
     * @param  array $languages
     * @return array
     */
    protected function checkLangs($languages)
    {
        if(!$languages) return array_map(function($item){ return $item->original;}, $this->languages->getAll());
        foreach($languages as $locale) {
            if($this->languages->has($locale)) continue;
            throw new LocaleNotFoundException($locale); // @codeCoverageIgnore
        }
        return $languages;
    }

    /**
     * Get the field descriptions of the specified content-type.
     * @param  string $type
     * @param  string $name
     * @return array
     */
    protected function fetchFields($type, $name)
    {
        $path = THEME_STRUCTURES . DS . $type . DS . $name . '.json';
        $config = File::loadJson($path);
        $fields = [];
        if(is_object($config) && isset($config->fields)){
            foreach($config->fields as $key => $field){
                $fields[$key] = $this->formatFieldContent($key, $field);
            }
        }
        return $fields;
    }

    /**
     * Creates content file for a model
     * @param string $model 
     * @param string $lang 
     * @param array $fields 
     * @return void
     */
    protected function generateObjectFile($model, $lang, $fields)
    {
        $path = $this->dir(ROOT_PATH . DS . 'content' . DS . $lang . DS . 'objects' . DS . $model);
        $files = scandir($path);
        $id = intval(pathinfo($files[count($files) - 1], PATHINFO_FILENAME));
        $file = $path . DS . (++$id);
        File::writeJson($fields, $file);
    }


    /**
     * Make a content file.
     * @param  string $name
     * @param  string $type
     * @param  string $lang
     * @param  object $fields
     * @return void
     */
    protected function generateContentFile($name, $type, $lang, $fields = null)
    {
        $file = $this->dir(ROOT_PATH . DS . 'content' . DS . $lang . DS . $type) . DS . $name;
        if(!$fields) $fields = new \stdClass;
        $content = [];
        if($type == 'pages'){
            $content['route'] = '';
            $content['title'] = '';
        }
        $content['id'] = $name;
        $content['template'] = $name;
        $content['data'] = $fields;
        if($type == 'menus'){
            $content['items'] = [];
        }
        echo "\nWriting file to " . $file;
        File::writeJson($content, $file);
    }

    /**
     * Format the field so it can be filled with content later.
     * @param  string $key
     * @param  object $field
     * @return mixed
     */
    protected function formatFieldContent($key, $field)
    {
        switch($field->type ?? 'text'){
            case 'image': return $this->getImageFieldContent($key, $field); break;
            case 'number': return $this->getNumberFieldContent($key, $field); break;
            case 'checkbox':
            case 'radio':
            case 'select': return $this->getSelectableFieldContent($key, $field); break;
        }
        if(!is_null($field->default ?? null)) return $field->default;
        return '';
    }

    /**
     * Generates a pre-filled object for an image field
     * @param string $key 
     * @param object $field 
     * @return object
     */
    protected function getImageFieldContent($key, $field)
    {
        $image = new \stdClass();
        $image->path = '';
        $image->alt = '';
        if(!isset($field->default)) return $image;
        if(!is_object($field->default)){
            throw new TypeException('default value for image field "' . $key . '" is invalid.'); // @codeCoverageIgnore
        }
        $image->path = is_string($field->default->path ?? null) ? $field->default->path : '';
        $image->alt = is_string($field->default->alt ?? null) ? $field->default->alt : '';
        return $image;
    }

    /**
     * Generates a pre-filled object for a number field
     * @param string $key 
     * @param object $field 
     * @return object
     */
    protected function getNumberFieldContent($key, $field)
    {
        if(is_null($field->default ?? null)) return 0;
        if(is_numeric($field->default)) return $field->default;
        throw new TypeException('default value for number field "' . $key . '" is invalid.'); // @codeCoverageIgnore
    }

    /**
     * Generates a pre-filled object for a selectable field
     * @param string $key 
     * @param object $field 
     * @return object
     */
    protected function getSelectableFieldContent($key, $field)
    {
        if(is_null($field->default ?? null)) return [];
        if(is_array($field->default)) return $field->default;
        throw new TypeException('default value for ' . $field->type . ' field "' . $key . '" is invalid.'); // @codeCoverageIgnore
    }

}