<?php
namespace App\Enums;

enum FeeStatus: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';

    public static function options(): array
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value
        ])->values()->toArray();
    }

}
