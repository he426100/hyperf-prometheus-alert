<?php

declare(strict_types=1);

namespace App\Lib\Posts;

use Guanguans\Notify\Factory;
use Guanguans\Notify\Messages\WeWork\MarkdownMessage;

class WeWork extends Post
{
    public function send()
    {
        return Factory::weWork()->setToken(env('WX_KEY'))->setMessage(new MarkdownMessage($this->text))->send();
    }
}
