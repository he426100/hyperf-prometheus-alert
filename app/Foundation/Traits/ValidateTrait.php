<?php

declare(strict_types=1);

namespace App\Foundation\Traits;

use App\Constants\ErrorCode;
use App\Exception\ValidateException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

trait ValidateTrait
{
    #[Inject]
    protected ValidatorFactoryInterface $validationFactory;

    public function validateData(array $data, string $scene)
    {
        $validator = $this->validationFactory->make($data, $this->validates[$scene], $this->messages);
        if ($validator->fails()) {
            Throw new ValidateException(ErrorCode::ERR_VALIDATION, $validator->errors()->first());
        }
    }
}