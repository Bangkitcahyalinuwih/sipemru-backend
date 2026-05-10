<?php

namespace Database\Seeders;

use App\Models\{Building, Room, Schedule, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // USERS
        User::create([
            'name' => 'Admin SIMARU',
            'email' => 'admin@simaru.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Affdilla',
            'email' => 'dilla@mhs.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
            'nim' => '21051001',
            'jurusan' => 'TRPL',
        ]);
        User::create([
            'name' => 'Silfina',
            'email' => 'fina@mhs.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
            'nim' => '21051002',
            'jurusan' => 'TEKLIS',
        ]);

        // GEDUNG
        $mnuh = Building::create([
            'name' => 'Gedung M.Nuh',
            'campus' => 'Kampus 1 Serayu',
            'address' => 'Jalan Serayu No. 84, Kelurahan Pandean, Kecamatan Taman, Kota Madiun, Jawa Timur',
            'floors' => 3,
        ]);
        $gedungA = Building::create([
            'name' => 'Gedung A',
            'campus' => 'Kampus 2 Ring Road Winongo',
            'address' => 'Jl. Ring Road Barat, Winongo, Kec. Manguharjo, Kota Madiun, Jawa Timur 63162',
            'description' => 'Administrasi Bisnis (Adbis)',
            'floors' => 4,
        ]);
        $gedungB = Building::create([
            'name' => 'Gedung B',
            'campus' => 'Kampus 2 Ring Road Winongo',
            'address' => 'Jl. Ring Road Barat, Winongo, Kec. Manguharjo, Kota Madiun, Jawa Timur 63162',
            'description' => 'Akutansi (AK)',
            'floors' => 4,
        ]);
        $gedungC = Building::create([
            'name' => 'Gedung C',
            'campus' => 'Kampus 2 Ring Road Winongo',
            'address' => 'Jl. Ring Road Barat, Winongo, Kec. Manguharjo, Kota Madiun, Jawa Timur 63162',
            'description' => 'Teknik (TEKNIK)',
            'floors' => 3,
        ]);

        // RUANGAN
        //MNUH KAMPUS 1
        //lab
        $lab203 = Room::create([
            'building_id' => $mnuh->id,
            'code' => 'B.203',
            'name' => 'Lab. Bahasa Inggris',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 2,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        $lab214 = Room::create([
            'building_id' => $mnuh->id,
            'code' => 'B.214',
            'name' => 'Lab. Jaringan Komputer',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 2,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        $lab216 = Room::create([
            'building_id' => $mnuh->id,
            'code' => 'B.216',
            'name' => 'Lab. Jaringan Komputer Dasar',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 2,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        $lab217 = Room::create([
            'building_id' => $mnuh->id,
            'code' => 'B.217',
            'name' => 'Lab. Pemrograman',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 2,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        $lab218 = Room::create([
            'building_id' => $mnuh->id,
            'code' => 'B.218',
            'name' => 'Lab. Multimedia',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 2,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        $lab301 = Room::create([
            'building_id' => $mnuh->id,
            'code' => 'B.301',
            'name' => 'Lab. Sistemcerdas',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 3,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        $lab305 = Room::create([
            'building_id' => $mnuh->id,
            'code' => 'B.305',
            'name' => 'Lab. Komputer TI',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 3,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        //auditorium
        $auditorium = Room::create([
            'building_id' => $mnuh->id,
            'code' => 'A. audit',
            'name' => 'Auditorium',
            'type' => 'auditorium',
            'capacity' => 200,
            'floor' => 1,
            'approval_type' => 'auto',
            'facilities' => ['Sound System', 'AC', 'Proyektor', 'Panggung'],
            'foto' => NULL
        ]);

        //KAMPUS 2 GEDUNG A
        //LABORATORIUM
        $labA305 = Room::create([
            'building_id' => $gedungA->id,
            'code' => 'A.305',
            'name' => 'Lab. Pemasaran Digital',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 3,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        $labA306 = Room::create([
            'building_id' => $gedungA->id,
            'code' => 'A.306',
            'name' => 'Lab. KombisPro',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 3,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        $labA307 = Room::create([
            'building_id' => $gedungA->id,
            'code' => 'A.307',
            'name' => 'Lab. BI',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 3,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        $labA308 = Room::create([
            'building_id' => $gedungA->id,
            'code' => 'A.308',
            'name' => 'Lab. Administrasi Bisnis',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 3,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        $labA309 = Room::create([
            'building_id' => $gedungA->id,
            'code' => 'A.309',
            'name' => 'Lab. Komputer',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 3,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);



        //RKB gedung A
        $rkbA = Room::create([
            'building_id' => $gedungA->id,
            'code' => 'A.rkb',
            'name' => 'Ruang Kelas Bersama',
            'type' => 'rkb',
            'capacity' => 100,
            'floor' => 2,
            'approval_type' => 'auto',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard', 'Sound System', 'Panggung'],
            'foto' => NULL
        ]);

        //GEDUNG B KAMPUS 2
        //rkb gedung B
        $rkbB406 = Room::create([
            'building_id' => $gedungB->id,
            'code' => 'RKB.406',
            'name' => 'Ruang Kelas Bersama 406',
            'type' => 'rkb',
            'capacity' => 100,
            'floor' => 2,
            'approval_type' => 'auto',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard', 'Sound System', 'Panggung'],
            'foto' => NULL
        ]);

        $rkbB407 = Room::create([
            'building_id' => $gedungB->id,
            'code' => 'RKB.407',
            'name' => 'Ruang Kelas Bersama 407',
            'type' => 'rkb',
            'capacity' => 100,
            'floor' => 2,
            'approval_type' => 'auto',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard', 'Sound System', 'Panggung'],
            'foto' => NULL
        ]);

        $rkbB408 = Room::create([
            'building_id' => $gedungB->id,
            'code' => 'RKB.408',
            'name' => 'Ruang Kelas Bersama 408',
            'type' => 'rkb',
            'capacity' => 100,
            'floor' => 2,
            'approval_type' => 'auto',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard', 'Sound System', 'Panggung'],
            'foto' => NULL
        ]);

        $rkbB409 = Room::create([
            'building_id' => $gedungB->id,
            'code' => 'RKB.409',
            'name' => 'Ruang Kelas Bersama 409',
            'type' => 'rkb',
            'capacity' => 100,
            'floor' => 2,
            'approval_type' => 'auto',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard', 'Sound System', 'Panggung'],
            'foto' => NULL
        ]);

        //lab gedung B
        $labB204 = Room::create([
            'building_id' => $gedungB->id,
            'code' => 'B.204',
            'name' => 'Lab. Komputer Akutansi 2',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 2,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        $labB302 = Room::create([
            'building_id' => $gedungB->id,
            'code' => 'B.302',
            'name' => 'Lab. Komputer Akutansi Sektor Publik 1',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 3,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        $labB303 = Room::create([
            'building_id' => $gedungB->id,
            'code' => 'B.303',
            'name' => 'Lab. Komputer Akutansi Sektor Publik 2',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 3,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        $labB402 = Room::create([
            'building_id' => $gedungB->id,
            'code' => 'B.402',
            'name' => 'Lab. Komputer Akutansi Perpajakan 1',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 4,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        $labB403 = Room::create([
            'building_id' => $gedungB->id,
            'code' => 'B.403',
            'name' => 'Lab. Komputer Akutansi Perpajakan 2',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 4,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        $labB404 = Room::create([
            'building_id' => $gedungB->id,
            'code' => 'B.404',
            'name' => 'Lab. Komputer Akutansi Perpajakan 3',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 4,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);


        //KAMPUS 2 GEDUNG C
        //lab
        $labC201 = Room::create([
            'building_id' => $gedungC->id,
            'code' => 'C.201',
            'name' => 'Lab. CAD',
            'type' => 'lab',
            'capacity' => 30,
            'floor' => 2,
            'approval_type' => 'manual',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard'],
            'foto' => NULL
        ]);

        //rkb
        $rkbC = Room::create([
            'building_id' => $gedungC->id,
            'code' => 'C.rkb',
            'name' => 'Ruang Kelas Bersama',
            'type' => 'rkb',
            'capacity' => 100,
            'floor' => 3,
            'approval_type' => 'auto',
            'facilities' => ['Komputer', 'AC', 'Proyektor', 'Whiteboard', 'Sound System', 'Panggung'],
            'foto' => NULL
        ]);

        // JADWAL 
        $jadwal = [
            //GEDUNG Kampus 2 gedung A (Akbis)
            //senin
            [
                'room_id' => $labA305->id,
                'course_name' => 'Pemasaran Digital',
                'lecturer' => 'Budi Hermawan',
                'prodi' => 'Pemasaran Digital',
                'kelas' => 'A',
                'sks' => 3,
                'day_of_week' => 'monday',
                'start_time' => '09:45',
                'end_time' => '12:15',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $labA309->id,
                'course_name' => 'Kewarganegaraan',
                'lecturer' => 'Dr. Ahmad Suryanto',
                'prodi' => 'Bahsa Inggris',
                'kelas' => 'A',
                'sks' => 2,
                'day_of_week' => 'monday',
                'start_time' => '12:45',
                'end_time' => '14:25',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'kuliah',
            ],
            //selasa
            [
                'room_id' => $labA306->id,
                'course_name' => 'Bahasa Ingris 2',
                'lecturer' => 'Siti Nurhaliza',
                'prodi' => 'KombisPro',
                'kelas' => 'B',
                'sks' => 3,
                'day_of_week' => 'tuesday',
                'start_time' => '09:45',
                'end_time' => '12:15',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $labA307->id,
                'course_name' => 'Grammer Dasar Bahasa Inggris',
                'lecturer' => 'Rina Wijaya',
                'prodi' => 'Bahasa Inggris',
                'kelas' => 'A',
                'sks' => 4,
                'day_of_week' => 'tuesday',
                'start_time' => '12:45',
                'end_time' => '18:00',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            //rabu
            [
                'room_id' => $labA308->id,
                'course_name' => 'Manajemen Sumber Daya Manusia',
                'lecturer' => 'Dhian Kusuma',
                'prodi' => 'Administrasi Bisnis',
                'kelas' => 'C',
                'sks' => 3,
                'day_of_week' => 'wednesday',
                'start_time' => '10:35',
                'end_time' => '12:15',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $labA309->id,
                'course_name' => 'Desain Komunikasi Visual',
                'lecturer' => 'Bambang Sutrisno',
                'prodi' => 'Pemasaran Digital',
                'kelas' => 'D',
                'sks' => 3,
                'day_of_week' => 'wednesday',
                'start_time' => '12:45',
                'end_time' => '18:00',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            //kamis
            [
                'room_id' => $labA305->id,
                'course_name' => 'Matematika',
                'lecturer' => 'Dr. Eko Putro',
                'prodi' => 'Pemasaran Digital',
                'kelas' => 'A',
                'sks' => 3,
                'day_of_week' => 'thursday',
                'start_time' => '10:35',
                'end_time' => '12:15',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $labA306->id,
                'course_name' => 'Personality Development',
                'lecturer' => 'Citra Dewi',
                'prodi' => 'KombisPro',
                'kelas' => 'B',
                'sks' => 2,
                'day_of_week' => 'thursday',
                'start_time' => '12:45',
                'end_time' => '16:20',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],

            // GEDUNG kampus 2 gedung B (Akutansi)
            //senin
            [
                'room_id' => $labB204->id,
                'course_name' => 'Praktikum Akutansi Dasar',
                'lecturer' => 'Ibu Siti Mariyah',
                'prodi' => 'Akutansi',
                'kelas' => 'A',
                'sks' => 3,
                'day_of_week' => 'monday',
                'start_time' => '07:00',
                'end_time' => '09:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $labB302->id,
                'course_name' => 'Praktikum Akutansi Sektor Publik',
                'lecturer' => 'Dr. Bambang Purwanto',
                'prodi' => 'Akutansi Sektor Publik',
                'kelas' => 'A',
                'sks' => 3,
                'day_of_week' => 'monday',
                'start_time' => '10:45',
                'end_time' => '13:15',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $labB402->id,
                'course_name' => 'Praktikum Perpajakan',
                'lecturer' => 'Bapak Hendra Wijaya',
                'prodi' => 'Akutansi Perpajakan',
                'kelas' => 'A',
                'sks' => 3,
                'day_of_week' => 'monday',
                'start_time' => '14:00',
                'end_time' => '16:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            //selasa
            [
                'room_id' => $labB204->id,
                'course_name' => 'Praktikum Akutansi Dasar',
                'lecturer' => 'Ibu Siti Mariyah',
                'prodi' => 'Akutansi',
                'kelas' => 'B',
                'sks' => 3,
                'day_of_week' => 'tuesday',
                'start_time' => '07:00',
                'end_time' => '09:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $labB303->id,
                'course_name' => 'Praktikum Akutansi Sektor Publik',
                'lecturer' => 'Dr. Bambang Purwanto',
                'prodi' => 'Akutansi Sektor Publik',
                'kelas' => 'B',
                'sks' => 3,
                'day_of_week' => 'tuesday',
                'start_time' => '10:45',
                'end_time' => '13:15',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $labB403->id,
                'course_name' => 'Praktikum Perpajakan',
                'lecturer' => 'Bapak Hendra Wijaya',
                'prodi' => 'Akutansi Perpajakan',
                'kelas' => 'B',
                'sks' => 3,
                'day_of_week' => 'tuesday',
                'start_time' => '14:00',
                'end_time' => '16:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            //rabu
            [
                'room_id' => $labB204->id,
                'course_name' => 'Praktikum Akutansi Intermediate',
                'lecturer' => 'Ibu Dian Kusuma',
                'prodi' => 'Akutansi',
                'kelas' => 'C',
                'sks' => 3,
                'day_of_week' => 'wednesday',
                'start_time' => '07:00',
                'end_time' => '09:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $labB302->id,
                'course_name' => 'Praktikum Sistem Informasi Akutansi',
                'lecturer' => 'Dr. Rini Suryanto',
                'prodi' => 'Akutansi Sektor Publik',
                'kelas' => 'C',
                'sks' => 3,
                'day_of_week' => 'wednesday',
                'start_time' => '10:45',
                'end_time' => '13:15',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $labB404->id,
                'course_name' => 'Praktikum Perpajakan',
                'lecturer' => 'Bapak Hendra Wijaya',
                'prodi' => 'Akutansi Perpajakan',
                'kelas' => 'C',
                'sks' => 3,
                'day_of_week' => 'wednesday',
                'start_time' => '14:00',
                'end_time' => '16:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            //kamis
            [
                'room_id' => $labB204->id,
                'course_name' => 'Praktikum Akutansi Lanjutan',
                'lecturer' => 'Prof. Agus Gunawan',
                'prodi' => 'Akutansi',
                'kelas' => 'D',
                'sks' => 3,
                'day_of_week' => 'thursday',
                'start_time' => '07:00',
                'end_time' => '09:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $labB303->id,
                'course_name' => 'Praktikum Audit',
                'lecturer' => 'Dr. Tri Sutrisno',
                'prodi' => 'Akutansi Sektor Publik',
                'kelas' => 'D',
                'sks' => 3,
                'day_of_week' => 'thursday',
                'start_time' => '10:45',
                'end_time' => '13:15',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $labB402->id,
                'course_name' => 'Praktikum Perencanaan Pajak',
                'lecturer' => 'Bapak Yusuf Rahman',
                'prodi' => 'Akutansi Perpajakan',
                'kelas' => 'D',
                'sks' => 3,
                'day_of_week' => 'thursday',
                'start_time' => '14:00',
                'end_time' => '16:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            //jumat
            [
                'room_id' => $labB302->id,
                'course_name' => 'Praktikum Akutansi Manajemen',
                'lecturer' => 'Ibu Retno Wijayanti',
                'prodi' => 'Akutansi Sektor Publik',
                'kelas' => 'E',
                'sks' => 3,
                'day_of_week' => 'friday',
                'start_time' => '10:45',
                'end_time' => '13:15',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $labB403->id,
                'course_name' => 'Praktikum Manajemen Pajak',
                'lecturer' => 'Bapak Eka Prasetyo',
                'prodi' => 'Akutansi Perpajakan',
                'kelas' => 'E',
                'sks' => 3,
                'day_of_week' => 'friday',
                'start_time' => '14:00',
                'end_time' => '16:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],

            //kampus 2 gedung C
            //senin
            [
                'room_id' => $labC201->id,
                'course_name' => 'Desain Autocad',
                'lecturer' => 'Ibu Retno Wijayanti',
                'prodi' => 'Teknologi Rekayasa Otomasi',
                'kelas' => 'A',
                'sks' => 3,
                'day_of_week' => 'monday',
                'start_time' => '01:00',
                'end_time' => '17:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            //jumat
            [
                'room_id' => $labC201->id,
                'course_name' => 'Desain Autocad',
                'lecturer' => 'Ibu Retno Wijayanti',
                'prodi' => 'Teknologi Rekayasa Otomasi',
                'kelas' => 'B',
                'sks' => 3,
                'day_of_week' => 'Friday',
                'start_time' => '07:00',
                'end_time' => '12:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],

            //KAMPUS 1 MNUH
            //senin

            [
                'room_id' => $lab203->id,
                'course_name' => 'Analisis Perancangan PL',
                'lecturer' => "Nisa'ul Hafidhoh",
                'prodi' => 'TRPL',
                'kelas' => 'A',
                'sks' => 3,
                'day_of_week' => 'monday',
                'start_time' => '07:00',
                'end_time' => '09:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab214->id,
                'course_name' => 'Praktikum Sistem Operasi',
                'lecturer' => 'Tri Septianto, S.Kom',
                'prodi' => 'TI',
                'kelas' => 'A',
                'sks' => 3,
                'day_of_week' => 'monday',
                'start_time' => '09:45',
                'end_time' => '12:15',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab218->id,
                'course_name' => 'Praktikum Multimedia Digital',
                'lecturer' => 'Siti Nurhayati',
                'prodi' => 'TRE',
                'kelas' => 'A',
                'sks' => 3,
                'day_of_week' => 'monday',
                'start_time' => '12:30',
                'end_time' => '15:00',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab305->id,
                'course_name' => 'Praktikum Komputer Terapan',
                'lecturer' => 'Andri Setiawan',
                'prodi' => 'TKK',
                'kelas' => 'A',
                'sks' => 3,
                'day_of_week' => 'monday',
                'start_time' => '15:15',
                'end_time' => '17:45',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab301->id,
                'course_name' => 'Praktikum Jaringan Komputer',
                'lecturer' => 'Rudi Santoso',
                'prodi' => 'TI',
                'kelas' => 'B',
                'sks' => 3,
                'day_of_week' => 'monday',
                'start_time' => '18:00',
                'end_time' => '19:10',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],

            //selasa
            [
                'room_id' => $lab301->id,
                'course_name' => 'OOP',
                'lecturer' => 'Bayu Prasetyo Utomo, S.Kom.',
                'prodi' => 'TRPL',
                'kelas' => 'A',
                'sks' => 3,
                'day_of_week' => 'tuesday',
                'start_time' => '07:00',
                'end_time' => '09:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab214->id,
                'course_name' => 'Praktikum Jaringan Komputer Lanjut',
                'lecturer' => 'Dian Permana',
                'prodi' => 'TI',
                'kelas' => 'B',
                'sks' => 3,
                'day_of_week' => 'tuesday',
                'start_time' => '09:45',
                'end_time' => '12:15',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab218->id,
                'course_name' => 'Praktikum Multimedia Interaktif',
                'lecturer' => 'Maya Anggraini',
                'prodi' => 'TRE',
                'kelas' => 'B',
                'sks' => 3,
                'day_of_week' => 'tuesday',
                'start_time' => '12:30',
                'end_time' => '15:00',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab305->id,
                'course_name' => 'Praktikum Aplikasi Desain',
                'lecturer' => 'Rita Hamzah',
                'prodi' => 'TKK',
                'kelas' => 'B',
                'sks' => 3,
                'day_of_week' => 'tuesday',
                'start_time' => '15:15',
                'end_time' => '17:45',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab203->id,
                'course_name' => 'Praktikum Pemrograman Web',
                'lecturer' => 'Bambang Saputra',
                'prodi' => 'TI',
                'kelas' => 'C',
                'sks' => 3,
                'day_of_week' => 'tuesday',
                'start_time' => '18:00',
                'end_time' => '19:10',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],

            //rabu
            [
                'room_id' => $lab216->id,
                'course_name' => 'Sistem Operasi',
                'lecturer' => 'Tri Septianto, S.Kom',
                'prodi' => 'TRPL',
                'kelas' => 'A',
                'sks' => 3,
                'day_of_week' => 'wednesday',
                'start_time' => '07:00',
                'end_time' => '09:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab214->id,
                'course_name' => 'Praktikum Jaringan Komputer',
                'lecturer' => 'Budi Santoso',
                'prodi' => 'TI',
                'kelas' => 'C',
                'sks' => 3,
                'day_of_week' => 'wednesday',
                'start_time' => '09:45',
                'end_time' => '12:15',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab218->id,
                'course_name' => 'Praktikum Multimedia Interaktif',
                'lecturer' => 'Siti Haryati',
                'prodi' => 'TRE',
                'kelas' => 'C',
                'sks' => 3,
                'day_of_week' => 'wednesday',
                'start_time' => '12:30',
                'end_time' => '15:00',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab305->id,
                'course_name' => 'Praktikum Aplikasi Komputer',
                'lecturer' => 'Andi Wijaya',
                'prodi' => 'TKK',
                'kelas' => 'C',
                'sks' => 3,
                'day_of_week' => 'wednesday',
                'start_time' => '15:15',
                'end_time' => '17:45',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab203->id,
                'course_name' => 'Praktikum Pengembangan Aplikasi',
                'lecturer' => 'Dewi Kusuma',
                'prodi' => 'TI',
                'kelas' => 'D',
                'sks' => 3,
                'day_of_week' => 'wednesday',
                'start_time' => '18:00',
                'end_time' => '19:10',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],

            //kamis
            [
                'room_id' => $lab214->id,
                'course_name' => 'Praktikum Sistem Jaringan',
                'lecturer' => 'Reni Kurniawati',
                'prodi' => 'TI',
                'kelas' => 'A',
                'sks' => 3,
                'day_of_week' => 'thursday',
                'start_time' => '07:00',
                'end_time' => '09:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab301->id,
                'course_name' => 'Struktur Data',
                'lecturer' => 'Ghali Marzani, M.Kom',
                'prodi' => 'TRPL',
                'kelas' => 'A',
                'sks' => 3,
                'day_of_week' => 'thursday',
                'start_time' => '09:45',
                'end_time' => '12:15',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab218->id,
                'course_name' => 'Praktikum Desain Multimedia',
                'lecturer' => 'Ika Prasetya',
                'prodi' => 'TRE',
                'kelas' => 'B',
                'sks' => 3,
                'day_of_week' => 'thursday',
                'start_time' => '12:30',
                'end_time' => '15:00',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab305->id,
                'course_name' => 'Praktikum Aplikasi Multimedia',
                'lecturer' => 'Hendra Saputra',
                'prodi' => 'TKK',
                'kelas' => 'B',
                'sks' => 3,
                'day_of_week' => 'thursday',
                'start_time' => '15:15',
                'end_time' => '17:45',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab203->id,
                'course_name' => 'Praktikum Aplikasi Basis Data',
                'lecturer' => 'Dewi Hartanti',
                'prodi' => 'TI',
                'kelas' => 'C',
                'sks' => 3,
                'day_of_week' => 'thursday',
                'start_time' => '18:00',
                'end_time' => '19:10',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],

            //jumat
            [
                'room_id' => $lab217->id,
                'course_name' => 'Pemograman Web',
                'lecturer' => 'Abdul Aziz',
                'prodi' => 'TRPL',
                'kelas' => 'A',
                'sks' => 3,
                'day_of_week' => 'friday',
                'start_time' => '07:00',
                'end_time' => '09:30',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab214->id,
                'course_name' => 'Praktikum Jaringan Komputer Lanjut',
                'lecturer' => 'Rini Sulistya',
                'prodi' => 'TI',
                'kelas' => 'D',
                'sks' => 3,
                'day_of_week' => 'friday',
                'start_time' => '09:45',
                'end_time' => '12:15',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab218->id,
                'course_name' => 'Praktikum Multimedia Digital',
                'lecturer' => 'Feri Indra',
                'prodi' => 'TRE',
                'kelas' => 'D',
                'sks' => 3,
                'day_of_week' => 'friday',
                'start_time' => '12:30',
                'end_time' => '15:00',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab305->id,
                'course_name' => 'Praktikum Aplikasi Desain',
                'lecturer' => 'Yuniarto',
                'prodi' => 'TKK',
                'kelas' => 'E',
                'sks' => 3,
                'day_of_week' => 'friday',
                'start_time' => '15:15',
                'end_time' => '17:45',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],
            [
                'room_id' => $lab203->id,
                'course_name' => 'Praktikum Basis Data',
                'lecturer' => 'Dewi Novita',
                'prodi' => 'TI',
                'kelas' => 'E',
                'sks' => 3,
                'day_of_week' => 'friday',
                'start_time' => '18:00',
                'end_time' => '19:10',
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'jenis_kegiatan' => 'praktikum',
            ],

        ];

        foreach ($jadwal as $j) {
            Schedule::create($j);
        }
    }
}
