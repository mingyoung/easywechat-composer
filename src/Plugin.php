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
use Composer\Script\Event;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Script\ScriptEvents;
use Composer\Installer\PackageEvent;
use Composer\Plugin\PluginInterface;
use Composer\Installer\PackageEvents;
use Composer\EventDispatcher\EventSubscriberInterface;

class Plugin implements PluginInterface, EventSubscriberInterface, Capable
{
    protected $activated = true;

    /**
     * Apply plugin modifications to Composer.
     *
     * @param \Composer\Composer    $composer
     * @param \Composer\IO\IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        //
    }

    /**
     * @return array
     */
    public function getCapabilities()
    {
        return [
            'Composer\Plugin\Capability\CommandProvider' => 'EasyWeChatComposer\Commands\Provider',
        ];
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
        if ('overtrue/wechat' === $event->getOperation()->getPackage()->getName()) {
            $this->activated = false;
        }
    }

    /**
     * @param \Composer\Script\Event $event
     */
    public function postAutoloadDump(Event $event)
    {
        if (! $this->activated) {
            return;
        }

        $vendorPath = rtrim($event->getComposer()->getConfig()->get('vendor-dir'), '/');
        $manifest = new ManifestManager(
            $vendorPath, $vendorPath.'/easywechat-composer/easywechat-composer/extensions.php'
        );

        $manifest->unlink()->build();
    }
}
