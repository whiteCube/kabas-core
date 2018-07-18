<?php 

namespace Tests;

use Kabas\Cmd\Commander;
use Tests\HandlesOutput;
use Tests\RunsCommands;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\ArgumentMissingException;
use Kabas\Exceptions\CommandNotAllowedException;

class CommanderTest extends TestCase
{
    use CreatesApplication;
    use RunsCommands;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;
    protected $configbackup;

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $this->spoofConfig();
        if(!defined('THEMES_DIR')) define('THEMES_DIR', __DIR__ . DS . '..' . DS . 'TestTheme' . DS . 'themes');
        if(!defined('THEME')) define('THEME', 'FooTheme');
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        if(is_dir(THEMES_DIR . DS . THEME)) $this->rrmdir(THEMES_DIR . DS . THEME);
    }

    protected function spoofConfig()
    {
        $this->backupConfig();
        $spoof = __DIR__ . DS . '..' . DS . 'TestTheme' . DS . 'spoofs' . DS . 'site.php';
        copy($spoof, __DIR__ . DS . '..' . DS . 'TestTheme' . DS . 'config' . DS . 'site.php');
    }

    protected function backupConfig()
    {
        $this->configbackup = file_get_contents(__DIR__ . DS . '..' . DS . 'TestTheme' . DS . 'config' . DS . 'site.php');
    }

    protected function restoreConfig()
    {
        file_put_contents(__DIR__ . DS . '..' . DS . 'TestTheme' . DS . 'config' . DS . 'site.php', $this->configbackup);
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->expectOutputRegex('/Kabas Help/');
        $this->assertInstanceOf(Commander::class, $this->cmd([]));
    }

    /** @test */
    public function can_show_help()
    {
        $this->expectOutputRegex('/Kabas Help/');
        $this->cmd('help');
    }

    /** @test */
    public function can_show_help_if_no_commands_specified()
    {
        $this->expectOutputRegex('/Kabas Help/');
        $this->cmd('');
    }

    /** @test */
    public function can_initialise_a_new_theme()
    {
        $this->catch(function() {
            $this->cmd('make:theme', 'FooTheme');
            $this->assertTrue($this->hasDir('FooTheme'));
            $this->assertTrue($this->hasDir('FooTheme', 'controllers'));
            $this->assertTrue($this->hasDir('FooTheme', 'models'));
            $this->assertTrue($this->hasDir('FooTheme', 'lang'));
            $this->assertTrue($this->hasDir('FooTheme', 'structures'));
            $this->assertTrue($this->hasDir('FooTheme', 'views'));
        });
    }

    /** @test */
    public function can_create_files_for_new_templates()
    {
        $this->catch(function() {
            $this->cmd('make:template', 'foo');
            $this->assertTrue($this->hasFile('FooTheme', 'controllers', 'templates', 'Foo.php'));
            $this->assertTrue($this->hasFile('FooTheme', 'structures', 'templates', 'foo.json'));
            $this->assertTrue($this->hasFile('FooTheme', 'views', 'templates', 'foo.php'));
        });
    }

    /** @test */
    public function can_create_files_for_new_partials()
    {
        $this->catch(function() {
            $this->cmd('make:partial', 'bar');
            $this->assertTrue($this->hasFile('FooTheme', 'controllers', 'partials', 'Bar.php'));
            $this->assertTrue($this->hasFile('FooTheme', 'structures', 'partials', 'bar.json'));
            $this->assertTrue($this->hasFile('FooTheme', 'views', 'partials', 'bar.php'));
        });
    }

    /** @test */
    public function can_create_files_for_new_menus()
    {
        $this->catch(function() {
            $this->cmd('make:menu', 'baz');
            $this->assertTrue($this->hasFile('FooTheme', 'controllers', 'menus', 'Baz.php'));
            $this->assertTrue($this->hasFile('FooTheme', 'structures', 'menus', 'baz.json'));
            $this->assertTrue($this->hasFile('FooTheme', 'views', 'menus', 'baz.php'));
        });
    }

    /** @test */
    public function can_create_files_for_new_models()
    {
        $this->catch(function() {
            $this->cmd('make:model', 'news', 'eloquent');
            $this->assertTrue($this->hasFile('FooTheme', 'models', 'News.php'));
            $this->assertTrue($this->hasFile('FooTheme', 'structures', 'models', 'news.json'));
        });
    }

    /** @test */
    public function can_use_the_default_driver_when_making_a_model()
    {
        $this->catch(function() {
            $this->cmd('make:model', 'news');
            $this->assertTrue($this->hasFile('FooTheme', 'models', 'News.php'));
            $this->assertTrue($this->hasFile('FooTheme', 'structures', 'models', 'news.json'));
            $this->assertTrue($this->fileContains('Kabas\Database\Json\Model', 'FooTheme', 'models', 'News.php'));
        });
    }

    /** @test */
    public function can_create_content_files_for_pages()
    {
        $this->catch(function() {
            $this->cmd('make:template', 'foopage');
            $this->createFakeStructureFiles('templates');
            $this->cmd('content:page', 'foopage');
            $this->assertTrue($this->hasFile('..', '..', '..', 'content', 'en-GB', 'pages', 'foopage.json'));
            $this->assertTrue($this->hasFile('..', '..', '..', 'content', 'fr-FR', 'pages', 'foopage.json'));
            $this->rrmdir(__DIR__ . DS . '..' . DS . '..' . DS . 'content');
        });
    }

    /** @test */
    public function can_create_content_files_for_partials()
    {
        $this->catch(function() {
            $this->cmd('make:partial', 'foopartial');
            $this->createFakeStructureFiles('partials');
            $this->cmd('content:partial', 'foopartial');
            $this->assertTrue($this->hasFile('..', '..', '..', 'content', 'en-GB', 'partials', 'foopartial.json'));
            $this->assertTrue($this->hasFile('..', '..', '..', 'content', 'fr-FR', 'partials', 'foopartial.json'));
            $this->rrmdir(__DIR__ . DS . '..' . DS . '..' . DS . 'content');
        });
    }

    /** @test */
    public function can_create_content_files_for_menus()
    {
        $this->catch(function() {
            $this->cmd('make:menu', 'foomenu');
            $this->createFakeStructureFiles('menus');
            $this->cmd('content:menu', 'foomenu');
            $this->assertTrue($this->hasFile('..', '..', '..', 'content', 'en-GB', 'menus', 'foomenu.json'));
            $this->assertTrue($this->hasFile('..', '..', '..', 'content', 'fr-FR', 'menus', 'foomenu.json'));
            $this->rrmdir(__DIR__ . DS . '..' . DS . '..' . DS . 'content');
        });
    }

    /** @test */
    public function can_create_content_files_for_objects()
    {
        $this->catch(function() {
            $this->cmd('make:model', 'foonews', 'eloquent');
            $this->createFakeStructureFiles('objects');
            $this->cmd('content:object', 'foonews');
            $this->assertTrue($this->hasFile('..', '..', '..', 'content', 'en-GB', 'objects', 'foonews', '1.json'));
            $this->assertTrue($this->hasFile('..', '..', '..', 'content', 'fr-FR', 'objects', 'foonews', '1.json'));
            $this->rrmdir(__DIR__ . DS . '..' . DS . '..' . DS . 'content');
        });   
    }

    /** @test */
    public function can_create_content_files_for_only_specified_locales()
    {
        $this->catch(function() {
            $this->cmd('make:template', 'foopage');
            $this->createFakeStructureFiles('templates');
            $this->cmd('content:page', 'foopage', 'en-GB');
            $this->assertTrue($this->hasFile('..', '..', '..', 'content', 'en-GB', 'pages', 'foopage.json'));
            $this->assertFalse($this->hasFile('..', '..', '..', 'content', 'fr-FR', 'pages', 'foopage.json'));
            $this->rrmdir(__DIR__ . DS . '..' . DS . '..' . DS . 'content');
        });
    }

    /** @test */
    public function can_be_explicit_when_command_not_found()
    {
        $this->expectOutputRegex('/Command not found!/');
        $this->cmd('foo');
    }

    public function createFakeStructureFiles($type)
    {
        $spoof = __DIR__ . DS . '..' . DS . 'TestTheme' . DS . 'spoofs' . DS . 'fields.json';
        if($type == 'templates') {
            copy($spoof, THEMES_DIR . DS . THEME . DS . 'structures' . DS . 'templates' . DS . 'foopage.json');
        }
        if($type == 'partials') {
            copy($spoof, THEMES_DIR . DS . THEME . DS . 'structures' . DS . 'partials' . DS . 'foopartial.json');
        }
        if($type == 'menus') {
            copy($spoof, THEMES_DIR . DS . THEME . DS . 'structures' . DS . 'menus' . DS . 'foomenu.json');
        }        
        if($type == 'objects') {
            copy($spoof, THEMES_DIR . DS . THEME . DS . 'structures' . DS . 'models' . DS . 'foonews.json');
        }
    }

    /** @test */
    public function throws_exception_if_missing_args()
    {
        $this->expectOutputRegex('/Missing argument/');
        $this->cmd('make:theme');
    }

    public function tearDown()
    {
        $this->restoreConfig();
        if(is_dir(THEMES_DIR . DS . THEME)) $this->rrmdir(THEMES_DIR . DS . THEME);
    }

    public function rrmdir($dir) { 
        if(realpath($dir) == '/') { throw new CommandNotAllowedException('Attempted a recursive deletion on "/" (rm -rf /)'); die(); }
        if (is_dir($dir)) { 
            $objects = scandir($dir);
            foreach ($objects as $object) { 
                if ($object != '.' && $object != '..') { 
                    if (is_dir($dir.'/'.$object))
                        $this->rrmdir($dir.'/'.$object);
                    else
                        unlink($dir.'/'.$object); 
                } 
            }
            rmdir($dir); 
        } 
    }

    public function hasDir(...$dirs)
    {
        $path = THEMES_DIR . DS;
        foreach($dirs as $dir) {
            $path .= $dir . DS;
        }
        return is_dir($path);
    }

    public function hasFile(...$pathFragments)
    {
        $path = THEMES_DIR;
        foreach($pathFragments as $pathFragment) {
            $path .= DS . $pathFragment;
        }
        return file_exists($path);
    }

    public function deleteFile(...$pathFragments)
    {
        $path = THEMES_DIR;
        foreach($pathFragments as $pathFragment) {
            $path .= DS . $pathFragment;
        }
        return unlink($path);
    }

    public function fileContains($needle, ...$pathFragments)
    {
        $path = THEMES_DIR;
        foreach($pathFragments as $pathFragment) {
            $path .= DS . $pathFragment;
        }
        $contents = file_get_contents($path);
        return strpos($contents, $needle) !== false;
    }

}