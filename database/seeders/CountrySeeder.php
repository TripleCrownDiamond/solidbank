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
            ['name' => 'Shqipëria', 'code' => 'AL', 'dial_code' => '+355', 'flag' => 'al'],  // Albanie
            ['name' => 'Deutschland', 'code' => 'DE', 'dial_code' => '+49', 'flag' => 'de'],  // Allemagne
            ['name' => 'Andorra', 'code' => 'AD', 'dial_code' => '+376', 'flag' => 'ad'],  // Andorre
            ['name' => 'Österreich', 'code' => 'AT', 'dial_code' => '+43', 'flag' => 'at'],  // Autriche
            ['name' => 'België / Belgique', 'code' => 'BE', 'dial_code' => '+32', 'flag' => 'be'],  // Belgique
            ['name' => 'Беларусь', 'code' => 'BY', 'dial_code' => '+375', 'flag' => 'by'],  // Biélorussie
            ['name' => 'Bosna i Hercegovina', 'code' => 'BA', 'dial_code' => '+387', 'flag' => 'ba'],  // Bosnie-Herzégovine
            ['name' => 'България', 'code' => 'BG', 'dial_code' => '+359', 'flag' => 'bg'],  // Bulgarie
            ['name' => 'Κύπρος / Kıbrıs', 'code' => 'CY', 'dial_code' => '+357', 'flag' => 'cy'],  // Chypre
            ['name' => 'Hrvatska', 'code' => 'HR', 'dial_code' => '+385', 'flag' => 'hr'],  // Croatie
            ['name' => 'Danmark', 'code' => 'DK', 'dial_code' => '+45', 'flag' => 'dk'],  // Danemark
            ['name' => 'España', 'code' => 'ES', 'dial_code' => '+34', 'flag' => 'es'],  // Espagne
            ['name' => 'Eesti', 'code' => 'EE', 'dial_code' => '+372', 'flag' => 'ee'],  // Estonie
            ['name' => 'Suomi', 'code' => 'FI', 'dial_code' => '+358', 'flag' => 'fi'],  // Finlande
            ['name' => 'France', 'code' => 'FR', 'dial_code' => '+33', 'flag' => 'fr'],  // France
            ['name' => 'Ελλάδα', 'code' => 'GR', 'dial_code' => '+30', 'flag' => 'gr'],  // Grèce
            ['name' => 'Magyarország', 'code' => 'HU', 'dial_code' => '+36', 'flag' => 'hu'],  // Hongrie
            ['name' => 'Éire / Ireland', 'code' => 'IE', 'dial_code' => '+353', 'flag' => 'ie'],  // Irlande
            ['name' => 'Ísland', 'code' => 'IS', 'dial_code' => '+354', 'flag' => 'is'],  // Islande
            ['name' => 'Italia', 'code' => 'IT', 'dial_code' => '+39', 'flag' => 'it'],  // Italie
            ['name' => 'Kosovë / Kosovo', 'code' => 'XK', 'dial_code' => '+383', 'flag' => 'xk'],  // Kosovo
            ['name' => 'Latvija', 'code' => 'LV', 'dial_code' => '+371', 'flag' => 'lv'],  // Lettonie
            ['name' => 'Liechtenstein', 'code' => 'LI', 'dial_code' => '+423', 'flag' => 'li'],  // Liechtenstein
            ['name' => 'Lietuva', 'code' => 'LT', 'dial_code' => '+370', 'flag' => 'lt'],  // Lituanie
            ['name' => 'Luxembourg / Lëtzebuerg', 'code' => 'LU', 'dial_code' => '+352', 'flag' => 'lu'],  // Luxembourg
            ['name' => 'Северна Македонија', 'code' => 'MK', 'dial_code' => '+389', 'flag' => 'mk'],  // Macédoine du Nord
            ['name' => 'Malta', 'code' => 'MT', 'dial_code' => '+356', 'flag' => 'mt'],  // Malte
            ['name' => 'Moldova', 'code' => 'MD', 'dial_code' => '+373', 'flag' => 'md'],  // Moldavie
            ['name' => 'Monaco', 'code' => 'MC', 'dial_code' => '+377', 'flag' => 'mc'],  // Monaco
            ['name' => 'Црна Гора', 'code' => 'ME', 'dial_code' => '+382', 'flag' => 'me'],  // Monténégro
            ['name' => 'Norge', 'code' => 'NO', 'dial_code' => '+47', 'flag' => 'no'],  // Norvège
            ['name' => 'Nederland', 'code' => 'NL', 'dial_code' => '+31', 'flag' => 'nl'],  // Pays-Bas
            ['name' => 'Polska', 'code' => 'PL', 'dial_code' => '+48', 'flag' => 'pl'],  // Pologne
            ['name' => 'Portugal', 'code' => 'PT', 'dial_code' => '+351', 'flag' => 'pt'],  // Portugal
            ['name' => 'Česká republika', 'code' => 'CZ', 'dial_code' => '+420', 'flag' => 'cz'],  // République tchèque
            ['name' => 'România', 'code' => 'RO', 'dial_code' => '+40', 'flag' => 'ro'],  // Roumanie
            ['name' => 'United Kingdom', 'code' => 'GB', 'dial_code' => '+44', 'flag' => 'gb'],  // Royaume-Uni
            ['name' => 'Россия', 'code' => 'RU', 'dial_code' => '+7', 'flag' => 'ru'],  // Russie
            ['name' => 'San Marino', 'code' => 'SM', 'dial_code' => '+378', 'flag' => 'sm'],  // Saint-Marin
            ['name' => 'Србија', 'code' => 'RS', 'dial_code' => '+381', 'flag' => 'rs'],  // Serbie
            ['name' => 'Slovensko', 'code' => 'SK', 'dial_code' => '+421', 'flag' => 'sk'],  // Slovaquie
            ['name' => 'Slovenija', 'code' => 'SI', 'dial_code' => '+386', 'flag' => 'si'],  // Slovénie
            ['name' => 'Sverige', 'code' => 'SE', 'dial_code' => '+46', 'flag' => 'se'],  // Suède
            ['name' => 'Schweiz / Suisse', 'code' => 'CH', 'dial_code' => '+41', 'flag' => 'ch'],  // Suisse
            ['name' => 'Україна', 'code' => 'UA', 'dial_code' => '+380', 'flag' => 'ua'],  // Ukraine
            ['name' => 'Città del Vaticano', 'code' => 'VA', 'dial_code' => '+39', 'flag' => 'va'],  // Vatican
        ];

        // Insertion des données dans la table `countries`
        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'name' => $country['name'],
                'code' => $country['code'],
                'dial_code' => $country['dial_code'],
                'flag' => $country['flag'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
