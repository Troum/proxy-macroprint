<?php

namespace App\Http\Resources;

use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequisiteResource extends JsonResource
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
            'title' => self::retrieveData($this->resource, 'data.attributes.title'),
            'description' => self::retrieveData($this->resource, 'data.attributes.description'),
            'seo' => new SeoResource(self::retrieveData($this->resource, 'data.attributes.seo'))
        ];
    }
}
