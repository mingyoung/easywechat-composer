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

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    protected $activated = true;

    /**
     * Apply plugin modifications to Composer.
     *
     * @param Composer    $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * Listen events.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            PackageEvents::PRE_PACKAGE_UNINSTALL => 'prePackageUninstall',
            ScriptEvents::POST_AUTOLOAD_DUMP => 'postAutoloadDump',
        ];
    }

    /**
     * @param \Composer\Installer\PackageEvent
     */
    public function prePackageUninstall(PackageEvent $event)
    {
        $this->activated = false;
    }

    /**
     * @param \Composer\Script\Event $event
     */
    public function postAutoloadDump(Event $event)
    {
        if (!$this->activated) {
            return;
        }
        $vendorPath = rtrim($event->getComposer()->getConfig()->get('vendor-dir'), '/');
        $manifest = new ManifestManager(
            $vendorPath, $vendorPath.'/easywechat-composer/easywechat-composer/extensions.php'
        );

        $manifest->unlink()->build();
    }
}
