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

class DelegationException extends \Exception
{
    protected $exception;

    public function setException($exception)
    {
        $this->exception = $exception;

        return $this;
    }
}
