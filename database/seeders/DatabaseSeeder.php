<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('flights')->insert([
           [
            'name' => Str::random(10),
            'class' => Str::random(10).'a10',
            'image' => 'https://cdn.pixabay.com/photo/2012/04/12/23/47/car-30984_1280.png',
            'detail' => 'Lượt xem
689.264
Nội dung tải xuống
485.655
tiết kiệm
434
Máy ảnh
Canon EOS 7d
Loại phương tiện
JPG
Độ phân giải
5122 x 3391
Ngày đăng
4 tháng 11, 2012',
           ]
           ,
           [
            'name' => Str::random(10),
            'class' => Str::random(10).'a10',
            'image' => 'https://cdn.pixabay.com/photo/2013/07/13/11/50/car-158795_640.png',
            'detail' => 'Lượt xem
689.264
Nội dung tải xuống
485.655
tiết kiệm
434
Máy ảnh
Canon EOS 7d
Loại phương tiện
JPG
Độ phân giải
5122 x 3391
Ngày đăng
4 tháng 11, 2012',
           ]
           ,  [
            'name' => Str::random(10),
            'class' => Str::random(10).'a10',
            'image' => 'https://cdn.pixabay.com/photo/2012/11/02/13/02/car-63930_640.jpg',
            'detail' => 'Lượt xem
689.264
Nội dung tải xuống
485.655
tiết kiệm
434
Máy ảnh
Canon EOS 7d
Loại phương tiện
JPG
Độ phân giải
5122 x 3391
Ngày đăng
4 tháng 11, 2012',
           ]
           
        ]);
    }
}
