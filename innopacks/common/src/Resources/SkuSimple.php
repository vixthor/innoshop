<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Common\Resources;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkuSimple extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     * @throws Exception
     */
    public function toArray(Request $request): array
    {
        $imagePath = $this->image ?? '';
        if (empty($imagePath)) {
            $imagePath = $this->product->image ?? '';
        }
        $imageUrl   = image_resize($imagePath);
        $finalPrice = $this->getFinalPrice();

        $productName = $this->product->fallbackName();

        $data = [
            'id'                  => $this->id,
            'product_id'          => $this->product_id,
            'product_name'        => $productName,
            'display_name'        => $this->code.' - '.$productName,
            'image'               => $imagePath,
            'image_url'           => $imageUrl,
            'variants'            => $this->variants,
            'variant_label'       => $this->variant_label,
            'model'               => $this->model,
            'code'                => $this->code,
            'price'               => $finalPrice,
            'price_format'        => currency_format($finalPrice),
            'origin_price'        => $this->origin_price,
            'origin_price_format' => $this->origin_price_format,
            'quantity'            => $this->quantity,
        ];

        return fire_hook_filter('resource.sku.simple', $data);
    }
}
