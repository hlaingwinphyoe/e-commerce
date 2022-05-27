<?php

namespace App\Models;

use App\Traits\SkuTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Milon\Barcode\DNS1D;

class Sku extends Model
{
    use HasFactory, SkuTrait;

    protected $table = 'skus';

    protected $guarded = [];

    protected $appends = ['price', 'discount', 'thumbnail', 'barcode', 'preorder_count', 'pricing_amt'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function gift_inventories()
    {
        return $this->hasMany(GiftInventory::class, 'sku_id', 'id');
    }

    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'sku_inventories', 'sku_id', 'inventory_id')->withPivot(['qty', 'rate', 'currency_id', 'amount', 'remark']);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'sku_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function pricings()
    {
        return $this->belongsToMany(Pricing::class, 'sku_pricing', 'sku_id', 'pricing_id');
    }

    public function costs()
    {
        return $this->belongsToMany(Cost::class, 'sku_cost', 'sku_id', 'cost_id');
    }

    public function wastes()
    {
        return $this->belongsToMany(Waste::class, 'sku_waste', 'sku_id', 'waste_id');
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'sku_discount', 'sku_id', 'discount_id');
    }

    public function medias()
    {
        return $this->morphToMany(Media::class, 'mediabble');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_sku', 'sku_id', 'order_id')->withPivot(['qty', 'price', 'customized_price', 'buy_price','margin', 'status_id']);
    }

    public function stockOrders()
    {
        return $this->hasMany(StockOrder::class, 'sku_id', 'id');
    }

    public function returns()
    {
        return $this->belongsToMany(ReturnModel::class, 'return_sku', 'sku_id', 'return_id')->withPivot(['qty', 'price', 'remark']);
    }

    public function getBarCode($width = 1, $height = 30)
    {
        // return DNS1D::getBarcodeHTML($this->code, 'C128', $width, $height);
        return $this->code ? '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($this->code, 'C128', 2, 50) . '" alt="barcode" class="w-100"   />' : '';
    }


    public function getBarcodeAttribute()
    {
        return $this->code ? '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($this->code, 'C128', 2, 50) . '" alt="barcode" class="w-100"  />' : '';
    }

    public function getPreorderCountAttribute()
    {
        $sku = Sku::preOrderCount($this->id)->first();
        
        return $sku ? $sku->sku_count : 0;
    }
}
