<?php

declare(strict_types=1);

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
use ReflectionClass;

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

        if ($this->shouldIgnore()) {
            return $observers;
        }

        foreach (require $packages as $name => $extra) {
            foreach ($extra['observers'] ?? [] as $observer) {
                if ($this->validateObserver($observer)) {
                    $observers[] = [$observer, $this->getObserverCondition($observer)];
                }
            }
        }

        return $observers;
    }

    /**
     * @param mixed $observer
     *
     * @return bool
     */
    protected function isDisable($observer): bool
    {
        return in_array($observer, $this->app->config->get('disable_observers', []), true);
    }

    /**
     * Get the observers should be ignore.
     *
     * @return bool
     */
    protected function shouldIgnore()
    {
        return !file_exists(__DIR__.'/../extensions.php') || $this->isDisable('*');
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
        return !$this->isDisable($observer)
            && (new ReflectionClass($observer))->implementsInterface(EventHandlerInterface::class)
            && $this->accessible($observer);
    }

    /**
     * Determine whether the given observer is accessible.
     *
     * @param string $observer
     *
     * @return bool
     */
    protected function accessible($observer)
    {
        if (!method_exists($observer, 'getAccessor')) {
            return true;
        }

        return in_array(get_class($this->app), (array) $observer::getAccessor());
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
