<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticlesResource;
use App\Http\Resources\SeoResource;
use App\Traits\HelperTrait;
use App\Http\Resources\Collections\ArticleCollection;
use App\Http\Resources\Collections\ProductCollection;
use App\Http\Resources\Collections\SlideCollection;
use App\Http\Resources\ProductResource;
use App\Http\Resources\RequirementResource;
use App\Http\Resources\RequisiteResource;
use Exception;
use Illuminate\Http\JsonResponse;

class PageController extends Controller
{
    use HelperTrait;
    /**
     * @return JsonResponse
     */
    public function indexPage(): JsonResponse
    {
        try {
            $index = $this->strapi->single('api/index', null, true, ['seo', 'slider.slides']);
            $products = $this->strapi->collection('api/products', 'createdAt', 'DESC', 1000, 0, true, ['image', 'icon']);
            $articles = $this->strapi->collection('api/articles', 'createdAt', 'DESC', 100000, 0, true, ['image']);
            $result = [
                'seo' => new SeoResource(self::retrieveData($index, 'data.attributes.seo')),
                'slides' => new SlideCollection(self::retrieveData($index, 'data.attributes.slider.slides.data')),
                'products' => new ProductCollection(self::retrieveData($products, 'data')),
                'articles' => new ArticleCollection(self::retrieveData($articles, 'data')),
                'vacancies' => []
            ];

            return response()->json($result);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage(), 'trace' => $exception->getTrace()]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function requisitesPage(): JsonResponse
    {
        try {
            $page = $this->strapi->single('api/requisite', null, true, ['seo']);
            return response()->json(new RequisiteResource($page));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage(), 'trace' => $exception->getTrace()]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function requirementsPage(): JsonResponse
    {
        try {
            $page = $this->strapi->single('api/technical-requirement', null, true, ['requirements', 'files.file', 'seo']);
            return response()->json(new RequirementResource($page));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage(), 'trace' => $exception->getTrace()]);
        }
    }

    /**
     * @param string $slug
     * @return JsonResponse
     */
    public function productPage(string $slug): JsonResponse
    {
        try {
            $product = $this->strapi->entriesByField('api/products', 'slug', $slug, true, ['image', 'icon', 'materials', 'examples', 'seo']);
            $products = $this->strapi->collection('api/products', 'createdAt', 'DESC', 1000, 0, true, ['image', 'icon']);
            $others = collect(self::retrieveData($products, 'data'))
                ->filter(function ($item) use ($product) {
                    return self::retrieveData($product, 'data.0.id') !== $item['id'];
                })->values();
            $product['data'][0]['others'] = $others;

            return response()->json(new ProductResource(self::retrieveData($product, 'data.0')));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage(), 'trace' => $exception->getTrace()]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function companyArticles(): JsonResponse
    {
        try {
            $articles = $this->strapi->collection('api/articles', 'createdAt', 'DESC', 100000, 0, true, ['image']);
            $page = $this->strapi->single('api/company-article', null, true, ['seo']);
            return response()->json([
                'articles' => new ArticleCollection(self::retrieveData($articles, 'data')),
                'page' => new ArticlesResource($page)
            ]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage(), 'trace' => $exception->getTrace()]);
        }
    }

    /**
     * @param string $slug
     * @return JsonResponse
     */
    public function companyArticle(string $slug): JsonResponse
    {
        try {
            $product = $this->strapi->entriesByField('api/products', 'slug', $slug, true, ['image', 'icon', 'materials', 'examples', 'seo']);
            $products = $this->strapi->collection('api/products', 'createdAt', 'DESC', 1000, 0, true, ['image', 'icon']);
            $others = collect($products['data'])->filter(function ($item) use ($product) {
                return $product['data'][0]['id'] !== $item['id'];
            })->values();
            $product['data'][0]['others'] = $others;

            return response()->json(new ProductResource($product['data'][0]));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage(), 'trace' => $exception->getTrace()]);
        }
    }
}
