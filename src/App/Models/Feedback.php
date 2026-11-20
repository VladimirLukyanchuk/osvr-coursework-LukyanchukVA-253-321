<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\ActiveRecord;

final class Feedback extends ActiveRecord
{
    protected static string $table = 'feedback';
    protected static array $fields = ['name', 'email', 'type', 'message'];
}
