<?php

use Illuminate\Database\Seeder;

class SecurityLabelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('security_labels')->insert([
            [
                'name' => 'rightclick',
                'message' => 'Context Menu not allowed'
            ],[
                'name' => 'rightclick_images',
                'message' => 'Context Menu on Images not allowed'
            ],[
                'name' => 'cut',
                'message' => 'Cut not allowed'
            ],[
                'name' => 'copy',
                'message' => 'Copy not allowed'
            ],[
                'name' => 'paste',
                'message' => 'Paste not allowed'
            ],[
                'name' => 'drag',
                'message' => 'Dragging is not allowed!'
            ],[
                'name' => 'drop',
                'message' => null
            ],[
                'name' => 'printscreen',
                'message' => 'It is not allowed to use the Print Screen button'
            ],[
                'name' => 'print',
                'message' => 'It is not allowed to Print'
            ],[
                'name' => 'view_source',
                'message' => 'It is not allowed to view the source code of the site'
            ],[
                'name' => 'iframe_out',
                'message' => null
            ],[
                'name' => 'selecting',
                'message' => null
            ]
        ]);
    }
}
