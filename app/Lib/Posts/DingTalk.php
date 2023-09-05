<?php

declare(strict_types=1);

namespace App\Lib\Posts;

use Guanguans\Notify\Factory;
use Guanguans\Notify\Messages\DingTalk\MarkdownMessage;

class DingTalk extends Post
{
    public function send()
    {
        return Factory::dingTalk()->setToken(env('DD_TOKEN'))->setSecret(env('DD_SECRET'))->setMessage(new MarkdownMessage([
            'title' => env('APP_NAME'),
            'text'  => $this->text,
        ]))->send();
    }
}
