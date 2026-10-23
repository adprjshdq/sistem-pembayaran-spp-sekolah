<?php

namespace App\Enums;

enum BillStatus: string
{
    case Unpaid = 'unpaid';
    case Partial = 'partial';
    case Paid = 'paid';

    public function label(): string
    {
        return match ($this) {
            self::Unpaid => 'Belum Dibayar',
            self::Partial => 'Dibayar Sebagian',
            self::Paid => 'Lunas',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Unpaid => 'bg-rose-50 text-rose-700 border-rose-200',
            self::Partial => 'bg-amber-50 text-amber-700 border-amber-200',
            self::Paid => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        };
    }
}
