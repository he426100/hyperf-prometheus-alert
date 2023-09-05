<?php

declare(strict_types=1);

namespace App\Lib\Posts;

abstract class Post
{
    public function __construct(protected string $text)
    {
    }

    public function send()
    {
    }
}
