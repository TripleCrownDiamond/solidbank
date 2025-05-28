<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Shqipëria', 'code' => 'AL', 'dial_code' => '+355'],  // Albanie
            ['name' => 'Deutschland', 'code' => 'DE', 'dial_code' => '+49'],  // Allemagne
            ['name' => 'Andorra', 'code' => 'AD', 'dial_code' => '+376'],  // Andorre
            ['name' => 'Österreich', 'code' => 'AT', 'dial_code' => '+43'],  // Autriche
            ['name' => 'België / Belgique', 'code' => 'BE', 'dial_code' => '+32'],  // Belgique
            ['name' => 'Беларусь', 'code' => 'BY', 'dial_code' => '+375'],  // Biélorussie
            ['name' => 'Bosna i Hercegovina', 'code' => 'BA', 'dial_code' => '+387'],  // Bosnie-Herzégovine
            ['name' => 'България', 'code' => 'BG', 'dial_code' => '+359'],  // Bulgarie
            ['name' => 'Κύπρος / Kıbrıs', 'code' => 'CY', 'dial_code' => '+357'],  // Chypre
            ['name' => 'Hrvatska', 'code' => 'HR', 'dial_code' => '+385'],  // Croatie
            ['name' => 'Danmark', 'code' => 'DK', 'dial_code' => '+45'],  // Danemark
            ['name' => 'España', 'code' => 'ES', 'dial_code' => '+34'],  // Espagne
            ['name' => 'Eesti', 'code' => 'EE', 'dial_code' => '+372'],  // Estonie
            ['name' => 'Suomi', 'code' => 'FI', 'dial_code' => '+358'],  // Finlande
            ['name' => 'France', 'code' => 'FR', 'dial_code' => '+33'],  // France
            ['name' => 'Ελλάδα', 'code' => 'GR', 'dial_code' => '+30'],  // Grèce
            ['name' => 'Magyarország', 'code' => 'HU', 'dial_code' => '+36'],  // Hongrie
            ['name' => 'Éire / Ireland', 'code' => 'IE', 'dial_code' => '+353'],  // Irlande
            ['name' => 'Ísland', 'code' => 'IS', 'dial_code' => '+354'],  // Islande
            ['name' => 'Italia', 'code' => 'IT', 'dial_code' => '+39'],  // Italie
            ['name' => 'Kosovë / Kosovo', 'code' => 'XK', 'dial_code' => '+383'],  // Kosovo
            ['name' => 'Latvija', 'code' => 'LV', 'dial_code' => '+371'],  // Lettonie
            ['name' => 'Liechtenstein', 'code' => 'LI', 'dial_code' => '+423'],  // Liechtenstein
            ['name' => 'Lietuva', 'code' => 'LT', 'dial_code' => '+370'],  // Lituanie
            ['name' => 'Luxembourg / Lëtzebuerg', 'code' => 'LU', 'dial_code' => '+352'],  // Luxembourg
            ['name' => 'Северна Македонија', 'code' => 'MK', 'dial_code' => '+389'],  // Macédoine du Nord
            ['name' => 'Malta', 'code' => 'MT', 'dial_code' => '+356'],  // Malte
            ['name' => 'Moldova', 'code' => 'MD', 'dial_code' => '+373'],  // Moldavie
            ['name' => 'Monaco', 'code' => 'MC', 'dial_code' => '+377'],  // Monaco
            ['name' => 'Црна Гора', 'code' => 'ME', 'dial_code' => '+382'],  // Monténégro
            ['name' => 'Norge', 'code' => 'NO', 'dial_code' => '+47'],  // Norvège
            ['name' => 'Nederland', 'code' => 'NL', 'dial_code' => '+31'],  // Pays-Bas
            ['name' => 'Polska', 'code' => 'PL', 'dial_code' => '+48'],  // Pologne
            ['name' => 'Portugal', 'code' => 'PT', 'dial_code' => '+351'],  // Portugal
            ['name' => 'Česká republika', 'code' => 'CZ', 'dial_code' => '+420'],  // République tchèque
            ['name' => 'România', 'code' => 'RO', 'dial_code' => '+40'],  // Roumanie
            ['name' => 'United Kingdom', 'code' => 'GB', 'dial_code' => '+44'],  // Royaume-Uni
            ['name' => 'Россия', 'code' => 'RU', 'dial_code' => '+7'],  // Russie
            ['name' => 'San Marino', 'code' => 'SM', 'dial_code' => '+378'],  // Saint-Marin
            ['name' => 'Србија', 'code' => 'RS', 'dial_code' => '+381'],  // Serbie
            ['name' => 'Slovensko', 'code' => 'SK', 'dial_code' => '+421'],  // Slovaquie
            ['name' => 'Slovenija', 'code' => 'SI', 'dial_code' => '+386'],  // Slovénie
            ['name' => 'Sverige', 'code' => 'SE', 'dial_code' => '+46'],  // Suède
            ['name' => 'Schweiz / Suisse', 'code' => 'CH', 'dial_code' => '+41'],  // Suisse
            ['name' => 'Україна', 'code' => 'UA', 'dial_code' => '+380'],  // Ukraine
            ['name' => 'Città del Vaticano', 'code' => 'VA', 'dial_code' => '+39'],  // Vatican
        ];

        // Insertion des données dans la table `countries`
        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'name' => $country['name'],
                'code' => $country['code'],
                'dial_code' => $country['dial_code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
