<?php

namespace Database\Seeders;

use App;
use File;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $totalindex = 0;
        $files = File::files(public_path() . '/storage/book/11');

        $ebook = App\Models\Ebook::factory()->create();
        foreach (range(1, 10) as $i) {
            $type_options = array('file', 'folder');
            $type_picked = $type_options[mt_rand(0, 1)];
            $chapters = App\Models\Chapter::factory()->for($ebook)->create([
                'type' => $type_picked,
            ]);
            $max_pages = $type_picked == 'file' ? 1 : 10;
            $order = 1;
            foreach (range(1, mt_rand(1, $max_pages)) as $i) {
                $pages = App\Models\Page::factory()->for($chapters)->create([
                    'order' => $order++,
                ]);
                $file = pathinfo($files[$totalindex]);
                File::move(public_path() . '/storage/book/11/' . $file['basename'], public_path() . '/storage/book/11/' . $pages->original_name);
                $totalindex++;
            }
            $order = 1;
        }
    }
}
