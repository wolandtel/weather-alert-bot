<?php

namespace Interfaces;

use Classes\Dto\Message;

interface Alerter
{
    /** @return Message[] */
    public function getMessages(): array;
}
