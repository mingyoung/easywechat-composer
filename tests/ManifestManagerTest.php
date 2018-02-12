<?php

namespace EasyWeChatComposer\Tests;

use EasyWeChatComposer\ManifestManager;
use PHPUnit\Framework\TestCase;

class ManifestManagerTest extends TestCase
{
    private $vendorPath;
    private $manifestPath;

    protected function getManifestManager()
    {
        return new ManifestManager(
            $this->vendorPath = __DIR__.'/__fixtures__/vendor/',
            $this->manifestPath = __DIR__.'/__fixtures__/extensions.php'
        );
    }

    public function testUnlink()
    {
        $this->assertInstanceOf(ManifestManager::class, $this->getManifestManager()->unlink());
        $this->assertFalse(file_exists($this->manifestPath));
    }
}
