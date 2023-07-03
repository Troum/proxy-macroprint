<?php

namespace App\Http\Resources;

use App\Traits\HelperTrait;
use App\Http\Resources\Collections\ProductCollection;
use Dbfx\LaravelStrapi\LaravelStrapi;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    use HelperTrait;
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $strapi = new LaravelStrapi();
        return [
            'title' => self::retrieveData($this->resource, 'attributes.title'),
            'description' => self::retrieveData($this->resource, 'attributes.description'),
            'shortDescription' => self::retrieveData($this->resource, 'attributes.shortDescription'),
            'benefit' => self::retrieveData($this->resource, 'attributes.benefit'),
            'slug' => self::retrieveData($this->resource, 'attributes.slug'),
            'image' => self::retrieveData($this->resource, 'attributes.image.data.attributes.url'),
            'icon' => self::retrieveData($this->resource, 'attributes.icon.data.attributes.url'),
            'materials' => $this->when(array_key_exists('materials', self::retrieveData($this->resource, 'attributes')),
                function () use ($strapi) {
                return collect(self::retrieveData($this->resource, 'attributes.materials.data'))
                    ->map(function($item) use ($strapi) {
                        return [
                            'title' => self::retrieveData($item,'attributes.title'),
                            'description' => self::retrieveData($item, 'attributes.description'),
                            'image' => $this->retrieveImageForMaterial($strapi, $item['id'])
                        ];
                    })->toArray();
            }, null),
            'examples' => $this->when(array_key_exists('examples', $this->resource['attributes']), function () use ($strapi) {
                return collect($this->resource['attributes']['examples']['data'])
                    ->map(function($item) use ($strapi){
                        return [
                            'title' => $item['attributes']['title'],
                            'description' => $item['attributes']['description'],
                            'material' => $item['attributes']['material'],
                            'image' => $this->retrieveImageForExample($strapi, $item['id'])
                        ];
                    })->toArray();
            }, null),
            'others' => $this->when(array_key_exists('others', $this->resource), function () {
                return new ProductCollection($this->resource['others']);
            })
        ];
    }

    /**
     * @param LaravelStrapi $strapi
     * @param $id
     * @return string
     */
    private function retrieveImageForExample(LaravelStrapi $strapi, $id): string
    {
        $image = $strapi->entry('api/examples', $id, true, ['image']);
        return self::retrieveData($image, 'data.attributes.image.data.attributes.url');
    }

    /**
     * @param LaravelStrapi $strapi
     * @param $id
     * @return string
     */
    private function retrieveImageForMaterial(LaravelStrapi $strapi, $id): string
    {
        $image = $strapi->entry('api/materials', $id, true, ['image']);
        return self::retrieveData($image, 'data.attributes.image.data.attributes.url');
    }
}
