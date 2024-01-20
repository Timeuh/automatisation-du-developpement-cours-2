<?php

namespace App\Console;

use Faker;
use Illuminate\Support\Carbon;
use Slim\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDatabaseCommand extends Command
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('db:populate');
        $this->setDescription('Populate database');
    }

    protected function execute(InputInterface $input, OutputInterface $output ): int
    {
        $faker = Faker\Factory::create('fr_FR');

        $output->writeln('Populate database...');

        /** @var \Illuminate\Database\Capsule\Manager $db */
        $db = $this->app->getContainer()->get('db');

        $db->getConnection()->statement("SET FOREIGN_KEY_CHECKS=0");
        $db->getConnection()->statement("TRUNCATE `employees`");
        $db->getConnection()->statement("TRUNCATE `offices`");
        $db->getConnection()->statement("TRUNCATE `companies`");
        $db->getConnection()->statement("SET FOREIGN_KEY_CHECKS=1");

//creation de 2 entreprises
        $companiesData = [];
        for ($i = 1; $i <= 2; $i++) {
            $companyName = $faker->company;
            $phoneNumber = $faker->phoneNumber;
            $email = "contact@$companyName.com";
            $website = "https://$companyName.com/";
            $imageUrl = $faker->imageUrl;

            $companiesData[] = [
                'id' => $i,
                'name' => $companyName,
                'phone' => $phoneNumber,
                'email' => $email,
                'website' => $website,
                'image' => $imageUrl,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'head_office_id' => null,
            ];
        }

//insertion des données dans la table companies
        $db->getConnection()->table('companies')->insert($companiesData);

//creation de 4 bureaux
        $officesData = [];
        for ($i = 1; $i <= 4; $i++) {
            ${"c$i"} = $faker->city;
            $streetName = $faker->streetName;
            $postcode = $faker->postcode;
            $country = $faker->country;
            $email = $faker->email;

            $officesData[] = [
                'id' => $i,
                'name' => "Bureau de {${"c$i"}}",
                'address' => $streetName,
                'city' => ${"c$i"},
                'zip_code' => $postcode,
                'country' => $country,
                'email' => $email,
                'phone' => null,
                'company_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
//insertion des données dans la table offices
        $db->getConnection()->table('offices')->insert($officesData);

//creation des employés
        $employeesData = [];
        for ($i = 1; $i <= 8; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $email = $faker->email;
            $phoneNumber = $faker->phoneNumber;
            $jobTitle = $faker->unique()->jobTitle;

            $employeesData[] = [
                'id' => $i,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'office_id' => $i % 4 + 1,
                'email' => $email,
                'phone' => $phoneNumber,
                'job_title' => $jobTitle,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
//insertion des données dans la table employees
        $db->getConnection()->table('employees')->insert($employeesData);


        $db->getConnection()->statement("update companies set head_office_id = 1 where id = 1;");
        $db->getConnection()->statement("update companies set head_office_id = 3 where id = 2;");

        $output->writeln('Database created successfully!');
        return 0;
    }
}
