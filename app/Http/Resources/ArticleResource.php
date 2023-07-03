<?php

namespace App\Http\Resources;

use App\Traits\HelperTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    use HelperTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'title' => self::retrieveData($this->resource, 'attributes.title'),
            'description' => self::retrieveData($this->resource, 'attributes.description'),
            'image' => self::retrieveData($this->resource,'attributes.image.data.attributes.url'),
            'date' => Carbon::parse(self::retrieveData($this->resource, 'attributes.createdAt'))->format('d/m/Y')
        ];
    }
}
