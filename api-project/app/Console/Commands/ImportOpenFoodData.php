<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ImportHistory;
use Illuminate\Support\Facades\Http;

class ImportOpenFoodData extends Command
{
    protected $signature = 'food:import';
    protected $description = 'Importa dados de alimentos do Open Food Facts';

    public function handle()
    {
        $urlList = 'https://challenges.coode.sh/food/data/json/index.txt';
        $files = explode("\n", trim(Http::get($urlList)->body()));

        $productsImported = 0;

        foreach ($files as $file) {
            if ($productsImported >= 100) break;

            $url = "https://challenges.coode.sh/food/data/json/{$file}";
            $response = Http::get($url);

            if ($response->ok()) {
                $data = collect(json_decode($response->body(), true))->take(100);

                foreach ($data as $item) {
                    Product::updateOrCreate(
                        ['product_name' => $item['product_name'] ?? null],
                        [
                            'brands' => $item['brands'] ?? null,
                            'categories' => $item['categories'] ?? null,
                            'labels' => $item['labels'] ?? null,
                            'ingredients' => $item['ingredients_text'] ?? null,
                            'countries' => $item['countries'] ?? null,
                            'imported_t' => now(),
                            'status' => 'imported',
                        ]
                    );
                    $productsImported++;
                }
            }
        }

        ImportHistory::create([
            'imported_at' => now(),
            'products_imported' => $productsImported,
        ]);

        $this->info("Importação completa. Produtos importados: {$productsImported}");
    }
}
