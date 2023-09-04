<?php

declare (strict_types=1);
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("Server Error！")
     */
    const SERVER_ERROR = 500;
    /**
     * @Message("验证异常！")
     */
    const ERR_VALIDATION = 3002;
}