<?php

use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class FillTables extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $data = [];
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                'pseudo' => $faker->userName,
                'nom' => $faker->lastName,
                'prenom' => $faker->firstName(),
                'mail' => $faker->email,
                'dateInscription' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s'),
                'admin' => $faker->boolean,
                'password' => $faker->password(7, 7)
            ];
        }
        $this->table('user')
            ->insert($data)
            ->save();
            
        $data = [];                            
        for ($i = 0; $i < 20; $i++) {                
            $data[] = [
                'idAuteur' => rand(1,5),
                'titre' => $faker->sentence(),
                'image' =>'assets/img/seed/' . $faker->image(__DIR__.'/../../Web/assets/img/seed', 640, 480, null, false), 
                'chapo' => $faker->catchPhrase,
                'contenu' => $faker->text(1000),
                'slug' => $faker->slug(3),
                'dateCreation' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s'),
                'dateModif' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s')
            ];
        }
        $this->table('post')
            ->insert($data)
            ->save();

        $data = [];                            
        for ($i = 0; $i < 20; $i++) {                
            $data[] = [
                'idAuteur' => rand(1,5),
                'idArticle' => rand(1,20),
                'contenu' => $faker->text(200),
                'dateCreation' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s'),
                'valid' => $faker->boolean
            ];
        }
        $this->table('comment')
        ->insert($data)
        ->save();
    }
}
