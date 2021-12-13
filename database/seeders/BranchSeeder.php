<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Setting;
use App\Models\Cutoff;
use App\Models\Window;
use App\Models\Server;
class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch1 = Branch::create(["name" => "HEAD OFFICE-STO. ROSARIO", "product_key" => "11291963"]);
        $setting1 = Setting::create(["branch_id" => $branch1->id]);
        $cutoff = Cutoff::create(["branch_id" => $branch1->id]);
        $window1_1 = Window::create(["name" => "Window 1", "order" => 1, "branch_id" => $branch1->id]);
        $window2_1 = Window::create(["name" => "Window 2", "order" => 2, "branch_id" => $branch1->id]);
        $window3_1 = Window::create(["name" => "Window 3", "order" => 3, "branch_id" => $branch1->id]);
        $server1 = Server::create([
            "name" => $branch1->name . "'s" . " Server",
            "branch_id" => $branch1->id
        ]);

        $branch2 = Branch::create(["name" => "PORAC", "product_key" => "11291964"]);
        $setting1 = Setting::create(["branch_id" => $branch2->id]);
        $cutoff = Cutoff::create(["branch_id" => $branch2->id]);
        $window1_1 = Window::create(["name" => "Window 1", "order" => 1, "branch_id" => $branch2->id]);
        $window2_1 = Window::create(["name" => "Window 2", "order" => 2, "branch_id" => $branch2->id]);
        $window3_1 = Window::create(["name" => "Window 3", "order" => 3, "branch_id" => $branch2->id]);
        $server2 = Server::create([
            "name" => $branch2->name . "'s" . " Server",
            "branch_id" => $branch2->id
        ]);

        $branch3 = Branch::create(["name" => "NEPO, AC", "product_key" => "11291965"]);
        $setting1 = Setting::create(["branch_id" => $branch3->id]);
        $cutoff = Cutoff::create(["branch_id" => $branch3->id]);
        $window1_1 = Window::create(["name" => "Window 1", "order" => 1, "branch_id" => $branch3->id]);
        $window2_1 = Window::create(["name" => "Window 2", "order" => 2, "branch_id" => $branch3->id]);
        $window3_1 = Window::create(["name" => "Window 3", "order" => 3, "branch_id" => $branch3->id]);
        $server3 = Server::create([
            "name" => $branch3->name . "'s" . " Server",
            "branch_id" => $branch3->id
        ]);

        $branch4 = Branch::create(["name" => "BALIBAGO", "product_key" => "11291966"]);
        $setting1 = Setting::create(["branch_id" => $branch4->id]);
        $cutoff = Cutoff::create(["branch_id" => $branch4->id]);
        $window1_1 = Window::create(["name" => "Window 1", "order" => 1, "branch_id" => $branch4->id]);
        $window2_1 = Window::create(["name" => "Window 2", "order" => 2, "branch_id" => $branch4->id]);
        $window3_1 = Window::create(["name" => "Window 3", "order" => 3, "branch_id" => $branch4->id]);
        $server4 = Server::create([
            "name" => $branch4->name . "'s" . " Server",
            "branch_id" => $branch4->id
        ]);

        $branch5 = Branch::create(["name" => "ARAYAT", "product_key" => "11291967"]);
        $setting1 = Setting::create(["branch_id" => $branch5->id]);
        $cutoff = Cutoff::create(["branch_id" => $branch5->id]);
        $window1_1 = Window::create(["name" => "Window 1", "order" => 1, "branch_id" => $branch5->id]);
        $window2_1 = Window::create(["name" => "Window 2", "order" => 2, "branch_id" => $branch5->id]);
        $window3_1 = Window::create(["name" => "Window 3", "order" => 3, "branch_id" => $branch5->id]);
        $server5 = Server::create([
            "name" => $branch5->name . "'s" . " Server",
            "branch_id" => $branch5->id
        ]);


        $branch6 = Branch::create(["name" => "MAGALANG", "product_key" => "11291968"]);
        $setting1 = Setting::create(["branch_id" => $branch6->id]);
        $cutoff = Cutoff::create(["branch_id" => $branch6->id]);
        $window1_1 = Window::create(["name" => "Window 1", "order" => 1, "branch_id" => $branch6->id]);
        $window2_1 = Window::create(["name" => "Window 2", "order" => 2, "branch_id" => $branch6->id]);
        $window3_1 = Window::create(["name" => "Window 3", "order" => 3, "branch_id" => $branch6->id]);
        $server6= Server::create([
            "name" => $branch6->name . "'s" . " Server",
            "branch_id" => $branch6->id
        ]);


        $branch7 = Branch::create(["name" => "SAN FERNANDO", "product_key" => "11291969"]);
        $setting1 = Setting::create(["branch_id" => $branch7->id]);
        $cutoff = Cutoff::create(["branch_id" => $branch7->id]);
        $window1_1 = Window::create(["name" => "Window 1", "order" => 1, "branch_id" => $branch7->id]);
        $window2_1 = Window::create(["name" => "Window 2", "order" => 2, "branch_id" => $branch7->id]);
        $window3_1 = Window::create(["name" => "Window 3", "order" => 3, "branch_id" => $branch7->id]);
        $server7 = Server::create([
            "name" => $branch7->name . "'s" . " Server",
            "branch_id" => $branch7->id
        ]);



        $branch8 = Branch::create(["name" => "MABALACAT", "product_key" => "11291910"]);
        $setting1 = Setting::create(["branch_id" => $branch8->id]);
        $cutoff = Cutoff::create(["branch_id" => $branch8->id]);
        $window1_1 = Window::create(["name" => "Window 1", "order" => 1, "branch_id" => $branch8->id]);
        $window2_1 = Window::create(["name" => "Window 2", "order" => 2, "branch_id" => $branch8->id]);
        $window3_1 = Window::create(["name" => "Window 3", "order" => 3, "branch_id" => $branch8->id]);
        $server8 = Server::create([
            "name" => $branch8->name . "'s" . " Server",
            "branch_id" => $branch8->id
        ]);



        $branch9 = Branch::create(["name" => "MEXICO", "product_key" => "11291911"]);
        $setting1 = Setting::create(["branch_id" => $branch9->id]);
        $cutoff = Cutoff::create(["branch_id" => $branch9->id]);
        $window1_1 = Window::create(["name" => "Window 1", "order" => 1, "branch_id" => $branch9->id]);
        $window2_1 = Window::create(["name" => "Window 2", "order" => 2, "branch_id" => $branch9->id]);
        $window3_1 = Window::create(["name" => "Window 3", "order" => 3, "branch_id" => $branch9->id]);
        $server9 = Server::create([
            "name" => $branch9->name . "'s" . " Server",
            "branch_id" => $branch9->id
        ]);



        $branch10 = Branch::create(["name" => "FLORIDA", "product_key" => "11291912"]);
        $setting1 = Setting::create(["branch_id" => $branch10->id]);
        $cutoff = Cutoff::create(["branch_id" => $branch10->id]);
        $window1_1 = Window::create(["name" => "Window 1", "order" => 1, "branch_id" => $branch10->id]);
        $window2_1 = Window::create(["name" => "Window 2", "order" => 2, "branch_id" => $branch10->id]);
        $window3_1 = Window::create(["name" => "Window 3", "order" => 3, "branch_id" => $branch10->id]);
        $server10 = Server::create([
            "name" => $branch10->name . "'s" . " Server",
            "branch_id" => $branch10->id
        ]);


        $branch11 = Branch::create(["name" => "PLARIDEL, AC", "product_key" => "11291913"]);
        $setting1 = Setting::create(["branch_id" => $branch11->id]);
        $cutoff = Cutoff::create(["branch_id" => $branch11->id]);
        $window1_1 = Window::create(["name" => "Window 1", "order" => 1, "branch_id" => $branch11->id]);
        $window2_1 = Window::create(["name" => "Window 2", "order" => 2, "branch_id" => $branch11->id]);
        $window3_1 = Window::create(["name" => "Window 3", "order" => 3, "branch_id" => $branch11->id]);
        $server11 = Server::create([
            "name" => $branch11->name . "'s" . " Server",
            "branch_id" => $branch11->id
        ]);


        $branch12 = Branch::create(["name" => "CONCEPCION", "product_key" => "11291914"]);
        $setting1 = Setting::create(["branch_id" => $branch12->id]);
        $cutoff = Cutoff::create(["branch_id" => $branch12->id]);
        $window1_1 = Window::create(["name" => "Window 1", "order" => 1, "branch_id" => $branch12->id]);
        $window2_1 = Window::create(["name" => "Window 2", "order" => 2, "branch_id" => $branch12->id]);
        $window3_1 = Window::create(["name" => "Window 3", "order" => 3, "branch_id" => $branch12->id]);
        $server12 = Server::create([
            "name" => $branch12->name . "'s" . " Server",
            "branch_id" => $branch12->id
        ]);


        $branch13 = Branch::create(["name" => "TARLAC", "product_key" => "11291915"]);
        $setting1 = Setting::create(["branch_id" => $branch13->id]);
        $cutoff = Cutoff::create(["branch_id" => $branch13->id]);
        $window1_1 = Window::create(["name" => "Window 1", "order" => 1, "branch_id" => $branch13->id]);
        $window2_1 = Window::create(["name" => "Window 2", "order" => 2, "branch_id" => $branch13->id]);
        $window3_1 = Window::create(["name" => "Window 3", "order" => 3, "branch_id" => $branch13->id]);
        $server13 = Server::create([
            "name" => $branch13->name . "'s" . " Server",
            "branch_id" => $branch13->id
        ]);

    }
}
