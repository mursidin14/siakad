<?php
namespace App\Enums;

enum MessageType: string
{
    case CREATED = 'created successfully';
    case UPDATED = 'updated successfully';
    case DELETED = 'deleted successfully';
    case ERROR = 'error, please try again later';

    public function message(string $entity='', ?string $error = null): string
    {
        if ($this === MessageType::ERROR && $error) {
            return "Failed to {$this->value} {$entity}. Error: {$error}";
        }

        return "{$this->value} {$entity}.";
    }
}
