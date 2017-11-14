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

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;

class Installer extends LibraryInstaller
{
    const EXTENSION_FILE = 'overtrue/wechat/extensions/packages.php';
    const PACKAGE_TYPE = 'easywechat-extension';
    const EXTRA_OBSERVER_NAME = 'observers';

    /**
     * @var array
     */
    protected $extensions;

    /**
     * Decides if the installer supports the given type.
     *
     * @param string $packageType
     *
     * @return bool
     */
    public function supports($packageType)
    {
        return $packageType === self::PACKAGE_TYPE;
    }

    /**
     * Installs specific package.
     *
     * @param InstalledRepositoryInterface $repo    repository in which to check
     * @param PackageInterface             $package package instance
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);

        $this->addExtension($package);
    }

    /**
     * Updates specific package.
     *
     * @param InstalledRepositoryInterface $repo    repository in which to check
     * @param PackageInterface             $initial already installed package version
     * @param PackageInterface             $target  updated version
     *
     * @throws InvalidArgumentException if $initial package is not installed
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        parent::update($repo, $initial, $target);

        $this->removeExtension($initial);
        $this->addExtension($target);
    }

    /**
     * Uninstalls specific package.
     *
     * @param InstalledRepositoryInterface $repo    repository in which to check
     * @param PackageInterface             $package package instance
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::uninstall($repo, $package);

        $this->removeExtension($package);
    }

    /**
     * Add extension.
     *
     * @param PackageInterface $package
     */
    protected function addExtension(PackageInterface $package)
    {
        $extension = [
            self::EXTRA_OBSERVER_NAME => $package->getExtra()[self::EXTRA_OBSERVER_NAME] ?? [],
        ];

        $extensions = $this->getExtensions();
        $extensions[$package->getName()] = $extension;

        $this->build($extensions);
    }

    /**
     * Remove extension.
     *
     * @param PackageInterface $package
     */
    protected function removeExtension(PackageInterface $package)
    {
        $extensions = $this->getExtensions();
        unset($extensions[$package->getName()]);

        $this->build($extensions);
    }

    /**
     * Get the easywechat extensions file path.
     *
     * @return string
     */
    protected function getExtensionPath(): string
    {
        return $this->vendorDir.'/'.self::EXTENSION_FILE;
    }

    /**
     * Get the current easywechat extensions.
     *
     * @return array
     */
    protected function getExtensions(): array
    {
        if (!is_null($this->extensions)) {
            return $this->extensions;
        }

        $this->filesystem->ensureDirectoryExists(dirname($this->getExtensionPath()));

        if (!file_exists($this->getExtensionPath())) {
            $this->build();
        }

        return $this->extensions = require $this->getExtensionPath();
    }

    /**
     * Build the extensions.
     *
     * @param array $extensions
     */
    protected function build(array $extensions = [])
    {
        file_put_contents(
            $this->getExtensionPath(), '<?php return '.var_export($extensions, true).';'
        );
    }
}
