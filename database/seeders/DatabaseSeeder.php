<?php

namespace Database\Seeders;

use App\Enums\BillStatus;
use App\Enums\UserRole;
use App\Models\AcademicYear;
use App\Models\Bill;
use App\Models\Fee;
use App\Models\SchoolClass;
use App\Models\Setting;
use App\Models\Student;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@edupay.test'],
            [
                'name' => 'Administrator Sekolah',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
                'is_active' => true,
            ],
        );

        $this->seedSettings();

        $academicYear = AcademicYear::query()->updateOrCreate(
            ['name' => '2026/2027'],
            [
                'start_date' => '2026-07-01',
                'end_date' => '2027-06-30',
                'is_active' => true,
            ],
        );

        AcademicYear::query()->whereKeyNot($academicYear->id)->update(['is_active' => false]);

        foreach (['X' => 300000, 'XI' => 350000, 'XII' => 400000] as $level => $amount) {
            Fee::query()->updateOrCreate(
                ['academic_year_id' => $academicYear->id, 'level' => $level],
                ['amount' => $amount],
            );
        }

        $classes = [
            ['name' => 'XII RPL 1', 'level' => 'XII', 'major' => 'Rekayasa Perangkat Lunak'],
            ['name' => 'XI RPL 1', 'level' => 'XI', 'major' => 'Rekayasa Perangkat Lunak'],
            ['name' => 'X RPL 1', 'level' => 'X', 'major' => 'Rekayasa Perangkat Lunak'],
            ['name' => 'XII TKJ 1', 'level' => 'XII', 'major' => 'Teknik Komputer dan Jaringan'],
            ['name' => 'XI TKJ 1', 'level' => 'XI', 'major' => 'Teknik Komputer dan Jaringan'],
            ['name' => 'X TKJ 1', 'level' => 'X', 'major' => 'Teknik Komputer dan Jaringan'],
        ];

        $names = [
            'Muhammad Rayhan Pratama', 'Adinda Putri Maharani', 'Farhan Rizky Maulana', 'Salsabila Nur Aisyah',
            'Daffa Pratama Nugroho', 'Nabila Zahra Putri', 'Rizky Aditya Saputra', 'Alya Rahmawati',
            'Bagas Dwi Kurniawan', 'Citra Kirana Dewi', 'Fajar Ramadhan', 'Intan Permata Sari',
            'Galih Prasetyo', 'Hana Safitri', 'Ilham Maulana', 'Kirana Ayu Lestari',
            'Joko Susilo', 'Larasati Putri', 'Krisna Bayu', 'Maya Anggraini',
            'Lukman Hakim', 'Nadia Rahma', 'Miko Ardiansyah', 'Putri Wulandari',
        ];

        $studentCounter = 0;
        $students = [];

        foreach ($classes as $classData) {
            $schoolClass = SchoolClass::query()->updateOrCreate(
                ['academic_year_id' => $academicYear->id, 'name' => $classData['name']],
                [
                    'level' => $classData['level'],
                    'major' => $classData['major'],
                    'homeroom_teacher' => 'Dewi Kartika, S.Pd.',
                ],
            );

            for ($i = 0; $i < 4; $i++) {
                $studentCounter++;
                $nis = '2026'.(1000 + $studentCounter);
                $name = $names[$studentCounter - 1];

                $user = User::query()->updateOrCreate(
                    ['email' => $nis.'@edupay.test'],
                    [
                        'name' => $name,
                        'password' => Hash::make('password'),
                        'role' => UserRole::Student,
                        'is_active' => true,
                    ],
                );

                $student = Student::query()->updateOrCreate(
                    ['nis' => $nis],
                    [
                        'user_id' => $user->id,
                        'class_id' => $schoolClass->id,
                        'name' => $name,
                        'gender' => $i % 2 === 0 ? 'male' : 'female',
                        'phone' => '0812'.str_pad((string) (30000000 + $studentCounter), 8, '0', STR_PAD_LEFT),
                        'parent_name' => 'Orang Tua '.$name,
                        'parent_phone' => '0813'.str_pad((string) (40000000 + $studentCounter), 8, '0', STR_PAD_LEFT),
                        'address' => 'Jl. Pendidikan No. '.$studentCounter.', Kota Bandung',
                        'status' => 'active',
                    ],
                );

                $fee = Fee::query()
                    ->where('academic_year_id', $academicYear->id)
                    ->where('level', $schoolClass->level)
                    ->first();

                if ($fee) {
                    for ($month = 7; $month <= 10; $month++) {
                        Bill::query()->firstOrCreate(
                            [
                                'student_id' => $student->id,
                                'period_month' => $month,
                                'period_year' => 2026,
                            ],
                            [
                                'academic_year_id' => $academicYear->id,
                                'amount' => $fee->amount,
                                'paid_amount' => 0,
                                'due_date' => Carbon::create(2026, $month, 10),
                                'status' => BillStatus::Unpaid,
                            ],
                        );
                    }
                }

                $students[] = $student;
            }
        }

        $this->seedPayments($students, $admin);
    }

    /**
     * @param  array<int, Student>  $students
     */
    private function seedPayments(array $students, User $admin): void
    {
        $paymentService = app(PaymentService::class);

        foreach ($students as $index => $student) {
            $counter = $index + 1;

            $months = match (true) {
                $counter === 1 => [7, 8],
                $counter % 5 === 0 => [7, 8, 9],
                $counter % 3 === 0 => [7],
                default => [7, 8],
            };

            $billIds = Bill::query()
                ->where('student_id', $student->id)
                ->where('period_year', 2026)
                ->whereIn('period_month', $months)
                ->whereIn('status', [BillStatus::Unpaid->value, BillStatus::Partial->value])
                ->pluck('id')
                ->all();

            if ($billIds === []) {
                continue;
            }

            $paymentService->createPayment(
                $student,
                $billIds,
                $counter % 4 === 0 ? 'transfer' : 'cash',
                $counter % 4 === 0 ? 'BCA Virtual Account' : 'Tunai',
                $admin,
                settleImmediately: true,
            );
        }
    }

    private function seedSettings(): void
    {
        $settings = [
            'school_name' => 'SMA/SMK Negeri 1 Teladan',
            'school_address' => 'Jl. Pendidikan No. 45, Kota Bandung, Jawa Barat',
            'school_phone' => '(022) 8876-1234',
            'school_email' => 'info@smkn1teladan.sch.id',
            'treasurer_name' => 'Hj. Siti Rahmawati, S.E.',
            'treasurer_nip' => '19780512 200312 2 001',
            'headmaster_name' => 'Dr. Ahmad Fauzi, M.Pd.',
            'receipt_note' => 'Kwitansi ini sah tanpa tanda tangan basah karena diterbitkan secara digital.',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
