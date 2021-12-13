<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = User::create([
            "username" => "admin",
            "password" => Hash::make("password123"),
            "is_admin" => true
        ]);

        $adminUser->profile()->save(
            new Profile([
                "first_name" => "Jomar",
                "last_name" => "Ramos",
                "middle_name" => "Franco",
                "user_id" => $adminUser->id
            ])
        );

        $data = '{
            "json_file": [{
                "username": "tillson.paul",
                "password": "peripatetics",
                "fullname": {
                    "first_name": "Paul",
                    "middle_name": "Sipe",
                    "last_name": "Tillson"
                },
                "branch_id": 1
            }, {
                "username": "lilly.jeffrey",
                "password": "annuities",
                "fullname": {
                    "first_name": "Jeffrey",
                    "middle_name": "Strawn",
                    "last_name": "Lilly"
                },
                "branch_id": 1
            }, {
                "username": "lovie.james",
                "password": "blurrily",
                "fullname": {
                    "first_name": "James",
                    "middle_name": "Ledford",
                    "last_name": "Lovie"
                },
                "branch_id": 1
            }, {
                "username": "janney.micheal",
                "password": "-stat",
                "fullname": {
                    "first_name": "Micheal",
                    "middle_name": "Smith",
                    "last_name": "Janney"
                },
                "branch_id": 2
            }, {
                "username": "ingole.jason",
                "password": "ileitis",
                "fullname": {
                    "first_name": "Jason",
                    "middle_name": "Spannaus",
                    "last_name": "Ingole"
                },
                "branch_id": 2
            }, {
                "username": "mcquillen.timothy",
                "password": null,
                "fullname": {
                    "first_name": "Timothy",
                    "middle_name": "Farrell",
                    "last_name": "Mcquillen"
                },
                "branch_id": 2
            }, {
                "username": "mello.ronald",
                "password": "statues",
                "fullname": {
                    "first_name": "Ronald",
                    "middle_name": "Moulton",
                    "last_name": "Mello"
                },
                "branch_id": 3
            }, {
                "username": "mccall.henry",
                "password": "pacifist",
                "fullname": {
                    "first_name": "Henry",
                    "middle_name": "Ostrowski",
                    "last_name": "Mccall"
                },
                "branch_id": 3
            }, {
                "username": "gann.andrew",
                "password": "unblinded",
                "fullname": {
                    "first_name": "Andrew",
                    "middle_name": "Bridges",
                    "last_name": "Gann"
                },
                "branch_id": 3
            }, {
                "username": "lewin.louis",
                "password": null,
                "fullname": {
                    "first_name": "Louis",
                    "middle_name": "Shortell",
                    "last_name": "Lewin"
                },
                "branch_id": 4
            }, {
                "username": "markovich.david",
                "password": "burgess-ship",
                "fullname": {
                    "first_name": "David",
                    "middle_name": "Anderson",
                    "last_name": "Markovich"
                },
                "branch_id": 4
            }, {
                "username": "tran.walter",
                "password": "consentaneously",
                "fullname": {
                    "first_name": "Walter",
                    "middle_name": "Mcmillan",
                    "last_name": "Tran"
                },
                "branch_id": 4
            }, {
                "username": "sairalampano",
                "password": "123",
                "fullname": {
                    "first_name": "Saira",
                    "middle_name": "",
                    "last_name": "Lampano"
                },
                "branch_id": 5
            }, {
                "username": "raejoshua",
                "password": "123",
                "fullname": {
                    "first_name": "Rae",
                    "middle_name": "",
                    "last_name": "Beltran"
                },
                "branch_id": 5
            }, {
                "username": "bouydavid",
                "password": "123",
                "fullname": {
                    "first_name": "Bouy",
                    "middle_name": "",
                    "last_name": "David"
                },
                "branch_id": 5
            }, {
                "username": "edemann.curtis",
                "password": "nonalignment",
                "fullname": {
                    "first_name": "Curtis",
                    "middle_name": "Clements",
                    "last_name": "Edemann"
                },
                "branch_id": 6
            }, {
                "username": "holmes.eric",
                "password": "poutine",
                "fullname": {
                    "first_name": "Eric",
                    "middle_name": "Gurnett",
                    "last_name": "Holmes"
                },
                "branch_id": 6
            }, {
                "username": "gayne.jame",
                "password": "bedwettings",
                "fullname": {
                    "first_name": "Jame",
                    "middle_name": "Beaufort",
                    "last_name": "Gayne"
                },
                "branch_id": 6
            }, {
                "username": "lopez.robert",
                "password": "quinzy",
                "fullname": {
                    "first_name": "Robert",
                    "middle_name": "Carter",
                    "last_name": "Lopez"
                },
                "branch_id": 7
            }, {
                "username": "waters.gene",
                "password": "sprite",
                "fullname": {
                    "first_name": "Gene",
                    "middle_name": "Spence",
                    "last_name": "Waters"
                },
                "branch_id": 7
            }, {
                "username": "abel.christopher",
                "password": "janitor",
                "fullname": {
                    "first_name": "Christopher",
                    "middle_name": "Trimble",
                    "last_name": "Abel"
                },
                "branch_id": 7
            }, {
                "username": "wright.thomas",
                "password": "forest-court",
                "fullname": {
                    "first_name": "Thomas",
                    "middle_name": "Terry",
                    "last_name": "Wright"
                },
                "branch_id": 8
            }, {
                "username": "carney.brian",
                "password": "inadaptation",
                "fullname": {
                    "first_name": "Brian",
                    "middle_name": "Woodson",
                    "last_name": "Carney"
                },
                "branch_id": 8
            }, {
                "username": "blair.nicholas",
                "password": "succourer",
                "fullname": {
                    "first_name": "Nicholas",
                    "middle_name": "Britten",
                    "last_name": "Blair"
                },
                "branch_id": 8
            }, {
                "username": "may.ryan",
                "password": "fizzing",
                "fullname": {
                    "first_name": "Ryan",
                    "middle_name": "Baker",
                    "last_name": "May"
                },
                "branch_id": 9
            }, {
                "username": "kay.charles",
                "password": "intralingual",
                "fullname": {
                    "first_name": "Charles",
                    "middle_name": "Butler",
                    "last_name": "Kay"
                },
                "branch_id": 9
            }, {
                "username": "garstka.alvaro",
                "password": "bottel",
                "fullname": {
                    "first_name": "Alvaro",
                    "middle_name": "Gould",
                    "last_name": "Garstka"
                },
                "branch_id": 9
            }, {
                "username": "foster.ryan",
                "password": "enamine",
                "fullname": {
                    "first_name": "Ryan",
                    "middle_name": "Watkins",
                    "last_name": "Foster"
                },
                "branch_id": 10
            }, {
                "username": "koenig.roland",
                "password": "apoop",
                "fullname": {
                    "first_name": "Roland",
                    "middle_name": "Sides",
                    "last_name": "Koenig"
                },
                "branch_id": 10
            }, {
                "username": "perkins.gary",
                "password": "backbeats",
                "fullname": {
                    "first_name": "Gary",
                    "middle_name": "Chapman",
                    "last_name": "Perkins"
                },
                "branch_id": 10
            }, {
                "username": "laschinger.william",
                "password": "minuted",
                "fullname": {
                    "first_name": "William",
                    "middle_name": "Wright",
                    "last_name": "Laschinger"
                },
                "branch_id": 11
            }, {
                "username": "thibault.walter",
                "password": "escitalopram",
                "fullname": {
                    "first_name": "Walter",
                    "middle_name": "Mitchel",
                    "last_name": "Thibault"
                },
                "branch_id": 11
            }, {
                "username": "leib.ernest",
                "password": "Voltaire",
                "fullname": {
                    "first_name": "Ernest",
                    "middle_name": "Ferland",
                    "last_name": "Leib"
                },
                "branch_id": 11
            }, {
                "username": "williams.gary",
                "password": "fish-slice",
                "fullname": {
                    "first_name": "Gary",
                    "middle_name": "Glover",
                    "last_name": "Williams"
                },
                "branch_id": 12
            }, {
                "username": "esquilin.robert",
                "password": "thioamides",
                "fullname": {
                    "first_name": "Robert",
                    "middle_name": "Jennings",
                    "last_name": "Esquilin"
                },
                "branch_id": 12
            }, {
                "username": "creekmore.richard",
                "password": "Syrophoenician",
                "fullname": {
                    "first_name": "Richard",
                    "middle_name": "Polito",
                    "last_name": "Creekmore"
                },
                "branch_id": 12
            }, {
                "username": "nordahl.christopher",
                "password": "rhodopsins",
                "fullname": {
                    "first_name": "Christopher",
                    "middle_name": "Scott",
                    "last_name": "Nordahl"
                },
                "branch_id": 13
            }, {
                "username": "jordan.gabriel",
                "password": "whippletree",
                "fullname": {
                    "first_name": "Gabriel",
                    "middle_name": "Eldridge",
                    "last_name": "Jordan"
                },
                "branch_id": 13
            }, {
                "username": "brothers.curtis",
                "password": "extracapsular",
                "fullname": {
                    "first_name": "Curtis",
                    "middle_name": "Lambrakis",
                    "last_name": "Brothers"
                },
                "branch_id": 13
            },{
                "username": "ramos.jomar",
                "password": "pasko",
		        "is_manager" : 1,
                "fullname": {
                    "first_name": "Jomar",
                    "middle_name": "Franco",
                    "last_name": "Ramos"
                },
                "branch_id": 1
            },{
                "username": "vinuya.navien",
                "password": "pasko",
                "is_manager" : 1,
                "fullname": {
                    "first_name": "Navien",
                    "middle_name": "",
                    "last_name": "Vinuya"
                },
                "branch_id": 2
            },{
                "username": "lampano.saira",
                "password": "pasko",
		        "is_manager" : 1,
                "fullname": {
                    "first_name": "Saira",
                    "middle_name": "",
                    "last_name": "Lampano"
                },
                "branch_id": 3
            },{
                "username": "beltran.joshua",
                "password": "pasko",
		        "is_manager" : 1,
                "fullname": {
                    "first_name": "Rae",
                    "middle_name": "Joshua",
                    "last_name": "Beltran"
                },
                "branch_id": 4
            },{
                "username": "jomarramos",
                "password": "123",
		        "is_manager" : 1,
                "fullname": {
                    "first_name": "Jomar",
                    "middle_name": "Franco",
                    "last_name": "Ramos"
                },
                "branch_id": 5
            },{
                "username": "luna.eljay",
                "password": "pasko",
		        "is_manager" : 1,
                "fullname": {
                    "first_name": "Eljay",
                    "middle_name": "",
                    "last_name": "Luna"
                },
                "branch_id": 6
            },{
                "username": "david.jester",
                "password": "pasko",
		        "is_manager" : 1,
                "fullname": {
                    "first_name": "Jester",
                    "middle_name": "",
                    "last_name": "David"
                },
                "branch_id": 7
            },{
                "username": "soriano.enriko",
                "password": "pasko",
		        "is_manager" : 1,
                "fullname": {
                    "first_name": "Enriko",
                    "middle_name": "",
                    "last_name": "Soriano"
                },
                "branch_id": 8
            },{
                "username": "manaloto.dave",
                "password": "pasko",
		        "is_manager" : 1,
                "fullname": {
                    "first_name": "Dave",
                    "middle_name": "",
                    "last_name": "Manaloto"
                },
                "branch_id": 9
            },{
                "username": "coloma.ryan",
                "password": "pasko",
		        "is_manager" : 1,
                "fullname": {
                    "first_name": "Ryan",
                    "middle_name": "",
                    "last_name": "Coloma"
                },
                "branch_id": 10
            },{
                "username": "lingat.eldrin",
                "password": "pasko",
		        "is_manager" : 1,
                "fullname": {
                    "first_name": "Eldrin",
                    "middle_name": "",
                    "last_name": "Lingat"
                },
                "branch_id": 11
            },{
                "username": "gonzales.jeans",
                "password": "pasko",
		        "is_manager" : 1,
                "fullname": {
                    "first_name": "Jeans",
                    "middle_name": "",
                    "last_name": "Gonzales"
                },
                "branch_id": 12
            },{
                "username": "gon.hisoka",
                "password": "pasko",
		        "is_manager" : 1,
                "fullname": {
                    "first_name": "Hisoka",
                    "middle_name": "",
                    "last_name": "Gon"
                },
                "branch_id": 13
            }]
        }';

        $data = json_decode($data, true)["json_file"];

        foreach($data as $user){
            $role = Role::all()->where("branch_id", "=", $user["branch_id"])->skip(1)->first();
            

            if(isset($user["is_manager"])){ 
                $role = Role::all()->where("branch_id", "=", $user["branch_id"])->where("name", "=", "Manager")->first();
            }else if(isset($user["is_server"])){
                $role = Role::all()->where("branch_id", "=", $user["branch_id"])->where("name", "=", "Server")->first();
            }else if(isset($user["is_it"])){
                $role = Role::all()->where("branch_id", "=", $user["branch_id"])->where("name", "=", "IT")->first();
            }
            

            $user1 = User::create([
                "username" => $user["username"],
                "password" => Hash::make($user["password"]),
                "is_admin" => false
            ]);
            $user1->profile()->save(
                new Profile([
                    "first_name" => $user["fullname"]["first_name"],
                    "last_name" =>  $user["fullname"]["last_name"],
                    "middle_name" => $user["fullname"]["middle_name"],
                    "user_id" => $user1->id,
                    "role_id" => $role->id,
                    "branch_id" => $user["branch_id"]
                ])
            );
            $user1->profile->services()->attach([1,2,3,4,5,6]);
        }



        

        
    }
}
