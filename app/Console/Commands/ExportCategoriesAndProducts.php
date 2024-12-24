<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Product;

class ExportCategoriesAndProducts extends Command
{
    protected $signature = 'export:categories-products {categoriesFile} {productsFile}';
    protected $description = 'Export categories and products from the database to JSON files';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $categoriesFile = $this->argument('categoriesFile');
        $productsFile = $this->argument('productsFile');

        // Экспорт категорий
        $categories = Category::all(['eId', 'title']); // Получаем все категории
        $this->exportToFile($categories, $categoriesFile);

        // Экспорт продуктов
        $products = Product::all(['eId', 'title', 'price']); // Получаем все продукты
        $this->exportToFile($products, $productsFile);

        $this->info('Categories and products have been successfully exported.');
    }

    private function exportToFile($data, $filePath)
    {
        if ($data->isEmpty()) {
            $this->error("No data found for export.");
            return;
        }

        // Преобразуем данные в массив и записываем в файл
        $dataArray = $data->toArray();
        file_put_contents($filePath, json_encode($dataArray, JSON_PRETTY_PRINT));
        $this->info("Data has been exported to {$filePath}");
    }
}
