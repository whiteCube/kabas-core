<?php 

namespace Kabas\Objects\Uploads;

use Kabas\Exceptions\NotFoundException;

class Item {

    protected $key;
    protected $data;
    protected $src;
    protected $folder = PUBLIC_PATH . DS . 'uploads';

    function __construct($key, $data)
    {
        $this->key = $key;
        $this->data = $data;
    }

    /**
     * Accessor for standard upload data (values found in $_FILES[$key])
     * @param string $key 
     * @return mixed
     */
    public function __get($key)
    {
        return $this->data[$key];
    }

    /**
     * Returns the path to the upload if it has been written to disk
     * @return string
     */
    public function src()
    {
        return $this->src;
    }

    /**
     * Writes the upload to disk. File name can be overridden.
     * @param string|null $name 
     * @return $this
     */
    public function save($name = null)
    {
        $this->prepare();
        $src = $this->getFullPath($name);
        if(!move_uploaded_file($this->tmp_name, $src)) {
            $this->copy($name);
        }
        $this->src = $src;
        return $this;
    }

    /**
     * Creates a copy of the upload under a different name
     * @param string $name 
     * @throws NotFoundException when trying to copy a file that has not been saved on disk yet
     * @return $this
     */
    public function copy($name)
    {
        if(is_null($this->src)) throw new NotFoundException('Could not find "' . $this->name . '" on disk to perform a copy. Please save it first.', 'file');
        copy($this->src, $this->getFullPath($name));
        return $this;
    }

    /**
     * Returns the upload's path
     * @param type|null $name 
     * @return string
     */
    protected function getFullPath($name = null)
    {
        if(is_null($name)) $name = $this->key . '-' . date('d-m-Y-H-m-i') . '.' . $this->getExtension();
        return $this->folder . DS . $name;
    }

    /**
     * Return the extension for the current upload
     * @return string
     */
    public function getExtension()
    {
        return strtolower(pathinfo($this->name, PATHINFO_EXTENSION));
    }

    /**
     * Makes sure the uploads folder exists
     * @return void
     */
    protected function prepare()
    {
        if(!is_dir($this->folder)) mkdir($this->folder, 0777, true);
    }

}