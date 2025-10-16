<?php
namespace App\Services;


use App\Models\ActivityLog;


class ActivityService
{
public static function log(string $action, array $meta = [], ?int $userId = null): void
{
ActivityLog::create([
'user_id' => $userId ?? auth()->id(),
'action' => $action,
'meta' => $meta,
'occurred_at' => now(),
]);
}
}