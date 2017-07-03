<?php

namespace Kabas\Exceptions\Whoops;

use Kabas\App;
use Kabas\Utils\Log;
use Kabas\Utils\Text;
use Kabas\Http\Response;
use Whoops\Handler\Handler;
use \Whoops\Handler\PrettyPageHandler;

class KabasPrettyPageHandler extends PrettyPageHandler
{
    /**
     * Handles the exception and decides how to display it
     * @return mixed
     */
    public function handle()
    {
        ob_clean();
        $this->prepare();
        Log::error($this->exception->getMessage() . PHP_EOL . $this->exception->getTraceAsString());
        if(App::config()->get('app.debug')) return $this->showWhoopsError();
        return $this->showUserFriendlyError();
    }

    /**
     * Shows a page deemed safe to the public
     * aka without any sensitive information.
     * @return void
     */
    public function showUserFriendlyError()
    {
        $exception = $this->getExceptionName();
        $hint = $this->getHint();
        $path = $this->getPath();
        $code = $this->exception->getCode();
        $reporting = App::config()->get('app.reporting');

        if(App::content()->pages->has($code)) {
            return App::response()->init($code);
        }

        include(__DIR__ . DS . 'UserFriendlyErrorPage.php');
        die();
    }

    /**
     * Retrieves the exception for use in error pages
     * @return void
     */
    protected function prepare()
    {
        $this->inspector = $this->getInspector();
        $this->exception = $this->inspector->getException();
    }

    /**
     * Gets the name of the exception without its namespace
     * @return string
     */
    protected function getExceptionName()
    {
        return Text::removeNamespace(get_class($this->exception));
    }

    protected function getHint()
    {
        return isset($this->exception->hint) ? $this->exception->hint : false;
    }

    /**
     * Gets the exception's origin if it is defined
     * @return string
     */
    protected function getPath()
    {
        if(!isset($this->exception->path) || !$this->exception->path) return 'Location unknown';
        return str_replace(ROOT_PATH, '', $this->exception->path);
    }

    /**
     * Shows the Whoops Pretty Page (only in debug mode)
     * @return void
     */
    protected function showWhoopsError()
    {
        $this->addResourcePath(__DIR__);
        $this->addCustomCss('CustomWhoopsStyles.css');
        parent::handle();
    }

}
