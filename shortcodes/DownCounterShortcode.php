<?php
namespace Grav\Plugin\Shortcodes;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class DownCounterShortcode extends Shortcode {
    public function init() {
        $this->shortcode->getHandlers()->add('downcounter', function(ShortcodeInterface $sc) {
            $s = $sc->getContent();
            $twig = $this->twig;
            $params = $sc->getParameters();
            $config = $this->config->get('plugins.downcounter');
            // due_date is Parameter from shortcode:
            if (isset($params['due_date'])) {
                $due_date = $this->grav['twig']->processString($params['due_date']);
            } else $due_date = '';
            /*
             */
            $output = $twig->processTemplate('partials/downcounter.html.twig',
                [
                    'due_date' => $due_date
                ]);
            return $output;
        });
    }
}
