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

namespace EasyWeChatComposer\Exceptions;

class UnexpectedCodeException extends \Exception
{
    /**
     * @param array $resource
     *
     * @throws \EasyWeChatComposer\Exceptions\UnexpectedCodeException
     */
    public static function check($resource)
    {
        if (!isset($resource['errcode'])) {
            return;
        }

        $code = $resource['errcode'] ?? null;

        if ($code !== 0) {
            throw new self($resource['errmsg'] ?? '', $code);
        }
    }
}
