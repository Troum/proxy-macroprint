<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class SectionController extends Controller
{
    public function getHeader(): JsonResponse
    {
        try {
            $products = $this->strapi->collection('api/products', 'createdAt', 'DESC', 1000, 0, true, ['image', 'icon']);

            $productLinks = [
                'products_links' => collect($products['data'])
                    ->map(function ($item) {
                        return [
                            'title' => Arr::get($item, 'attributes.title'),
                            'route' => '/products/' . Arr::get($item, 'attributes.slug'),
                        ];
                    })
            ];
            return response()->json($productLinks);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage(), 'trace' => $exception->getTrace()]);
        }

    }

    public function getFooter()
    {

    }
}
