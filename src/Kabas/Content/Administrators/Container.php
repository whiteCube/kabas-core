<?php

namespace Kabas\Content\Administrators;

use \Kabas\App;
use \Kabas\Utils\File;
use \Kabas\Utils\Text;
use Kabas\Utils\Session;
use \Kabas\Content\BaseContainer;

class Container extends BaseContainer
{

    /**
     * Returns path to administrators directory
     * @return string
     */
    protected function getPath($lang = null)
    {
        return STORAGE_PATH . DS . 'administrators';
    }

    protected function makeItem($file, $encrypt = false)
    {
        return new Item($file, $encrypt);
    }

    public function create(array $data)
    {
        if(!$this->validate($data)) return false;
        $admin = $this->makeItem((object) $data, true);
        $admin->persist();
        $this->items[(string) $admin->username] = $admin;
        return $admin;
    }

    public function isAuthenticated()
    {
        return Session::has('_kabas.authenticated') && Session::get('_kabas.authenticated');
    }

    public function logout()
    {
        return Session::forget('_kabas.authenticated');
    }

    public function login($data)
    {
        $username = $data[0];
        $password = $data[1];
        if(!$this->has($username)) return false;
        $user = $this->get($username);
        return $user->login($password);
    }

    protected function validate(array $data)
    {
        return  isset($data['password']) 
                && isset($data['username']) 
                && !$this->has($data['username']);
    }

    /**
     * Recursively go through the files array to instanciate items
     * @param  array $files
     * @return array
     */
    protected function loop($files)
    {
        $items = [];
        foreach($files as $name => $file) {
            $file->id = $file->id ?? $this->extractNameFromFile($name);
            $file->template = $file->template ?? $this->extractNameFromFile($name);
            $file->username = $file->id;
            if(is_array($file)) $this->loop($file);
            else $items[$file->id] = $this->makeItem($file);
        }
        return $items;
    }
}
