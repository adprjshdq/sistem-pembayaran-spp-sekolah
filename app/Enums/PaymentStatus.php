<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Success = 'success';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::Success => 'Berhasil',
            self::Failed => 'Gagal',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-amber-50 text-amber-700 border-amber-200',
            self::Success => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::Failed => 'bg-rose-50 text-rose-700 border-rose-200',
        };
    }
}
