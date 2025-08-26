<?php
namespace App\Enums;

enum StudyPlanStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public static function options(): array
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value
        ])->values()->toArray();
    }

}
