<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class DownCounterPlugin
 * @package Grav\Plugin
 */
class DownCounterPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => [
                ['onPluginsInitialized', 0]
            ]
        ];
    }

    /**
     * Composer autoload
     *
     * @return ClassLoader
     */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main events we are interested in
        $this->enable([
            'onShortcodeHandlers' => ['onShortcodeHandlers', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onPageInitialized' => ['onPageInitialized', 0]
        ]);
    }

    public function onPageInitialized(Event $event)
    {
        /** @var Page */
        $page = $event['page'];

        //  if (($page->template() === 'calendar') || ($enableOnAllPages) || ($enableByPageHeader)) {
        $this->addAssets();
        //  }
    }

    private function addAssets()
    {
        /** @var Assets */
        $assets = $this->grav['assets'];
        $config = $this->config->get('plugins.downcounter');
        $assets->addJs('plugins://' . $this->name . '/assets/TimeCircles.min.js', ['group' => 'bottom']);
        $assets->addJs('plugins://' . $this->name . '/assets/dc.js', ['group' => 'bottom']);
        $assets->addCss('plugins://' . $this->name . '/assets/TimeCircles.css');
    }

    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    public function onShortcodeHandlers(Event $e)
    {
        $this->grav['shortcode']->registerAllShortcodes(__DIR__ . '/shortcodes');
    }

}
