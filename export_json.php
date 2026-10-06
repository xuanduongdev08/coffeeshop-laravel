<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = App\Models\Product::with('category')->get();
$sizes = App\Models\ProductSize::all()->groupBy('product_id');
$categories = App\Models\Category::all();

$productsArray = $products->map(function ($p) use ($sizes) {
    $item = $p->toArray();
    $item['sizes'] = isset($sizes[$p->id]) ? $sizes[$p->id]->values()->toArray() : [];
    return $item;
});

if (!is_dir(__DIR__ . '/public/data')) {
    mkdir(__DIR__ . '/public/data', 0777, true);
}

file_put_contents(__DIR__ . '/public/data/products.json', json_encode($productsArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents(__DIR__ . '/public/data/categories.json', $categories->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Successfully exported " . count($productsArray) . " products and " . $categories->count() . " categories.\n";
