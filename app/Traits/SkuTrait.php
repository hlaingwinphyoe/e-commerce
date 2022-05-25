<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Support\Facades\Storage;

use App\Models\ReturnModel;

trait SkuTrait
{
    /**
     * helper functions
     */

    public function getExchangeRate()
    {
        return $this->currency ? $this->currency->exchangerates()->latest()->first()->mmk : 1;
    }

    public function getPurePrice()
    {
        return ($this->pure_price * $this->getExchangeRate());
    }

    public function getRawCost()
    {
        $costs = $this->costs->sum(function ($cost) {
            return $cost->amt * $cost->currency->exchangerates()->latest()->first()->mmk;
        });

        return $this->getPurePrice() + $costs;
    }

    public function getWastes()
    {
        $wastes = 0;

        // $wastes = $this->wastes->sum(function ($waste) {
        //     return $waste->status->slug == 'percent' ? ($this->getRawCost() * $waste->amt) / 100 : $waste->amt;
        // });

        return $wastes;

        // return $this->getTotalWasteCount();
    }

    public function getPureCost()
    {
        return $this->getRawCost() + $this->getWastes();
    }

    public function getPriceRole()
    {
        return  auth()->check() && !in_array(auth()->user()->role->slug, ['admin', 'technician', 'manager', 'operator', 'owner']) ? auth()->user()->role : Role::where('slug', 'customer')->first();
    }

    public function getPriceAttribute()
    {
        /* NO NEED FOR QTY PRICING
        *  ENABLED THIS RELATION FOR MUTLI LEVEL PRICIING 
        */
        // $role = $this->getPriceRole();

        // $pricing_rate = $role->pricings()->whereHas('skus', function ($q) {
        //     $q->where('id', $this->id);
        // })->latest()->first();

        $pricing_rate = $this->pricings()->orderby('min_qty')->first();

        if ($pricing_rate) {

            $purecost = $this->getPureCost();

            $profit = $pricing_rate->status->slug == 'percent' ? ($purecost * $pricing_rate->amt) / 100 : $pricing_rate->amt;

            $price = $purecost + $profit;

            if ($this->pure_price == 0 && $this->currency_id !== 1) {
                $price = $price * $this->getExchangeRate();
            }

            // $mod = $price % 1000;

            // if ($price > 1000 && ($mod > 500 || $mod < 500)) {
            //     $price = round($price / 1000) * 1000;
            // }
            return $price;
        }

        return $this->getPureCost();
    }

    public function getDiscountAttribute()
    {
        $role = $this->getPriceRole();

        $discount = $role->discounts()->whereHas('items', function ($q) {
            $q->whereHas('skus', function ($q) {
                $q->where('id', $this->id);
            });
        })->latest()->first();

        $discount = $discount && ($discount->expired == null || \Carbon\Carbon::parse($discount->expired) >= \Carbon\Carbon::now()) ? $discount : null;

        if ($discount) {
            $discount_amt = $discount->status->slug == 'percent' ? (filter_var($this->price, FILTER_SANITIZE_NUMBER_INT) * $discount->amt) / 100 : $discount->amt;

            $discount_amt = filter_var($this->price, FILTER_SANITIZE_NUMBER_INT) - $discount_amt;

            return $discount_amt;
        }
        return 0;
    }

    public function getQtyOrgPrice($qty = 1)
    {
        $price = 0;

        if ($this->pricings->count() == 1) {
            $price = $this->price;
        } else {
            $max_pricing = $this->pricings()->orderBy('max_qty', 'desc')->first();

            $pricing = $this->pricings()->where('min_qty', '<=', $qty)->where('max_qty', '>=', $qty)->first();

            if ($pricing) {
                $price = $pricing->amt;
            } else {
                $price = $max_pricing ? $max_pricing->amt : 0;
            }
        }
        return $price;
    }

    public function getQtyPrice($qty = 1)
    {
        $discount = $this->item->getDiscount();

        $is_percent = $discount && $discount->status->slug == 'percent';

        $price = 0;

        if ($this->pricings->count() == 1) {
            if ($discount) {
                $discount_amt = $is_percent ? ($this->price * $discount->amt) / 100  : $discount->amt;
            } else {
                $discount_amt = 0;
            }
            $price = $this->price - $discount_amt;
        } else {
            $max_pricing = $this->pricings()->orderBy('max_qty', 'desc')->first();

            $pricing = $this->pricings()->where('min_qty', '<=', $qty)->where('max_qty', '>=', $qty)->first();

            if ($pricing) {
                $price = $pricing->amt;
            } else {
                $price = $max_pricing ? $max_pricing->amt : 0;
            }

            if ($discount) {
                $discount_amt = $is_percent ? ($price * $discount->amt) / 100  : $discount->amt;
            } else {
                $discount_amt = 0;
            }

            $price = $price - $discount_amt;
        }
        return $price;
    }

    //get attributes functions
    public function getThumbnailAttribute()
    {
        $img = $this->medias()->first();
        $path = '';
        if ($img) {
            if (Storage::exists('public/thumbnail/' . $img->slug)) {
                $path = Storage::url('public/thumbnail/' . $img->slug);
            } else {
                $path = $img->url;
            }
        } else {
            $path = asset('/images/featured/default.png');
        }
        return $path;
    }

    public function getPricingAmtAttribute()
    {
        return $this->pricings()->count() ? $this->pricings()->first()->amt : 0;
    }

    public function isCurrency($id)
    {
        return $this->currency_id === $id;
    }

    public function getReturnCount()
    {
        return $this->returns->reduce(function ($total, $return) {
            return $total + $return->pivot->qty;
        }, 0);
    }

    public function getTotalInventoriesCount()
    {
        $inventories = $this->inventories()->where('is_published', 1)->get();

        return $inventories->reduce(function ($total, $inventory) {
            return $total + $inventory->pivot->qty;
        }, 0);
    }

    public function getTotalSalesCount()
    {
        // $orders = $this->orders()->whereHas('status', function ($query) {
        //     $query->whereIn('slug', ['completed', 'cancel']);
        // })->get();

        $orders = $this->orders()->whereHas('status', function ($query) {
            $query->where('slug', 'completed');
        })->get();

        return $orders->reduce(function ($total, $order) {
            return $total + $order->pivot->qty;
        }, 0);
    }

    public function getTotalSalesCancelCount()
    {
        $orders = $this->orders()->whereHas('status', function ($query) {
            $query->whereIn('slug', ['cancel']);
        })->get();

        return $orders->reduce(function ($total, $order) {
            return $total + $order->pivot->qty;
        }, 0);
    }


    public function getTotalWaitCount()
    {
        $orders = $this->orders()->whereHas('status', function ($query) {
            $query->where('slug', 'order-confirmed');
        })->get();

        return $orders->reduce(function ($total, $order) {
            return $total + $order->pivot->qty;
        }, 0);
    }

    public function getGiftCount()
    {
        return $this->gift_inventories->reduce(function ($total, $inventory) {
            return $total + $inventory->qty;
        }, 0);
    }

    public function getTotalWasteCount($type)
    {
        if ($type != '!adjust') {
            $wastes = $this->wastes()->whereHas('status', function ($q) use ($type) {
                $q->where('slug', $type);
            })->get();
        } else {
            $wastes = $this->wastes()->whereHas('status', function ($q) {
                $q->where('slug', '!=', 'adjust');
            })->get();
        }

        return $wastes->reduce(function ($total, $waste) {
            return $total + $waste->amt;
        }, 0);
    }



    /**
     * scope functions
     */
    public function scopeEnabledItem($query)
    {
        $query->where('disabled', 0);
    }

    public function scopeAvailable($query)
    {
        $query->where('stock', '>', 0);
    }

    public function scopeDiscountType($query, $id)
    {
        $query->whereHas('discounts', function ($query) use ($id) {
            $query->where('discountype_id', $id);
        });
    }

    public function scopeFilterOn($query)
    {
        if (request('item')) {
            $query->where('item_id', request('item'));
        }

        if (request('type')) {
            $query->whereHas('item', function ($query) {
                $query->whereHas('types', function ($query) {
                    $query->where('id', request('type'))->orWhere('slug', request('type'));
                });
            });
        }

        if (request('brand')) {
            $query->whereHas('item', function ($query) {
                $query->whereHas('brands', function ($query) {
                    $query->where('id', request('brand'))->orWhere('slug', request('brand'));
                });
            });
        }

        if (request('maintype')) {
            $query->whereHas('item', function ($query) {
                $query->whereHas('types', function ($query) {
                    $query->whereHas('maintypes', function ($query) {
                        $query->where('id', request('maintype'));
                    });
                });
            });
        }

        if (request('q')) {
            $query->where('data', 'like', '%' . request('q') . '%')
                ->orWhere('code', 'like', '%' . request('q') . '%')
                ->orWhereHas('item', function ($query) {
                    $query->where('name', 'like', '%' . request('q') . '%');
                });
        }

        if (request('status') && request('status') == 'instock') {
            $query->where('stock', '>', 0);
        } else if (request('status') && request('status') == 'outofstock') {
            $query->where('stock', '<=', 0);
        }

        if (request('sort') && request('sort') == 'min_stock') {
            $query->orderBy('stock');
        } else if (request('sort') && request('sort') == 'max_stock') {
            $query->orderBy('stock', 'desc');
        } else if (!request('sort')) {
            $query->orderBy('stock');
        }
    }

    public function scopePreOrderCount($query, $id)
    {
        $query->selectRaw('
            skus.id AS sid, skus.item_id,
            SUM(order_sku.qty) AS sku_count
        ')->fromRaw('
            skus
            JOIN order_sku ON skus.id = order_sku.sku_id
            JOIN orders ON orders.id = order_sku.order_id
            JOIN statuses ON statuses.id = order_sku.status_id
            WHERE statuses.slug="pre-ordered" AND skus.id=?
            GROUP BY sid
        ', $id);
    }


    public function scopePreOrder($query)
    {
        $query->selectRaw('
            skus.id AS sid, skus.item_id,
            COUNT(skus.id) AS sku_count
        ')->fromRaw('
            skus
            JOIN order_sku ON skus.id = order_sku.sku_id
            JOIN orders ON orders.id = order_sku.order_id
            JOIN statuses ON statuses.id = order_sku.status_id
            WHERE statuses.slug="pre-ordered"
            GROUP BY sid
        ');
    }
}
