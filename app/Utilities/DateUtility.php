<?php

namespace App\Utilities;

use Carbon\Carbon;

class DateUtility
{
    public static function daysUntilDue(Carbon $dueDate): int
    {
        return (int) now()->startOfDay()->diffInDays($dueDate->startOfDay(), false);
    }

    public static function dueLabel(Carbon $dueDate): string
    {
        $days = self::daysUntilDue($dueDate);

        if ($days < 0) {
            return abs($days).' day'.(abs($days) === 1 ? '' : 's').' overdue';
        }

        if ($days === 0) {
            return 'Due today';
        }

        if ($days === 1) {
            return 'Due tomorrow';
        }

        return 'Due in '.$days.' days';
    }

    public static function dueUrgency(Carbon $dueDate): string
    {
        $days = self::daysUntilDue($dueDate);

        if ($days < 0) {
            return 'overdue';
        }

        if ($days <= 3) {
            return 'urgent';
        }

        if ($days <= 7) {
            return 'soon';
        }

        return 'normal';
    }
}
