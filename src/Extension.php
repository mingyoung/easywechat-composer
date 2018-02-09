<?php

/*
 * This file is part of the EasyWeChatComposer.
 *
 * (c) mingyoung <mingyoungcheung@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChatComposer;

use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\ServiceContainer;

class Extension
{
    /**
     * @var \EasyWeChat\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * @param \EasyWeChat\Kernel\ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * Get observers.
     *
     * @return array
     */
    public function observers(): array
    {
        $observers = [];

        $disableObservers = $this->app->config->get('disable_observers', []);
        $disableAll = in_array('*', $disableObservers, true);

        if (file_exists($packages = __DIR__.'/extensions.php')) {
            foreach (require $packages as $name => $extra) {
                foreach ($extra['observers'] ?? [] as $observer) {
                    if (!$disableAll && !in_array($observer, $disableObservers, true) && $this->validateObserver($observer)) {
                        $observers[] = [$observer, $this->getObserverCondition($observer)];
                    }
                }
            }
        }

        return $observers;
    }

    /**
     * Validate the given observer.
     *
     * @param mixed $observer
     *
     * @return bool
     */
    protected function validateObserver($observer): bool
    {
        return in_array(EventHandlerInterface::class, class_implements($observer), true) &&
            ($exists = method_exists($observer, 'getAccessor')) && (in_array(get_class($this->app), (array) $observer::getAccessor(), true)) || !$exists;
    }

    /**
     * Get the observer's condition.
     *
     * @param mixed $observer
     *
     * @return string|int
     */
    protected function getObserverCondition($observer)
    {
        return method_exists($observer, 'onCondition') ? $observer::onCondition() : '*';
    }
}
