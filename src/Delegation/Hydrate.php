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

namespace EasyWeChatComposer\Delegation;

use EasyWeChat\Factory;

class Hydrate
{
    /**
     * @var array
     */
    protected $attributes;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function handle()
    {
        $app = $this->createsApplication()->shouldntDelegate();

        foreach ($this->attributes['identifiers'] as $identifier) {
            $app = $app->$identifier;
        }

        return call_user_func_array([$app, $this->attributes['method']], $this->attributes['arguments']);
    }

    /**
     * @return \EasyWeChat\Kernel\ServiceContainer
     */
    protected function createsApplication()
    {
        $applications = [
            'EasyWeChat\OfficialAccount\Application' => 'OfficialAccount',
        ];

        return Factory::make(
            $applications[$this->attributes['application']], $this->attributes['config']
        );
    }
}
