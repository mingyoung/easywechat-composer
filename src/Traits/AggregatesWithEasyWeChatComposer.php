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

namespace EasyWeChatComposer\Traits;

use EasyWeChatComposer\Delegation;
use EasyWeChatComposer\EasyWeChat;

trait AggregatesWithEasyWeChatComposer
{
    protected function aggregation()
    {
        foreach (EasyWeChat::config() as $key => $value) {
            $this['config']->set($key, $value);
        }
    }

    /**
     * @return bool
     */
    public function shouldDelegate()
    {
        return (bool) $this['config']->get('delegation.enabled');
    }

    /**
     * @return $this
     */
    public function shouldntDelegate()
    {
        $this['config']->set('delegation.enabled', false);

        return $this;
    }

    /**
     * @param string $id
     *
     * @return \EasyWeChatComposer\Delegation
     */
    public function delegateTo($id)
    {
        return new Delegation($this, $id);
    }
}
