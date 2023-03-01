<?php

namespace App\Traits;

use App\Models\Role;

trait ItemTrait
{
    /**
     * helper functions
     */

    public function getPriceAttribute()
    {
        $prices = [];
        $prices = $this->skus->map(function ($sku) {
            return $sku->price;
        });

        if (count($prices) > 1) {
            $ary = [];
            foreach ($prices as $price) {
                array_push($ary, $price);
            }
            $max = max($ary);
            $min = min($ary);
            return $max > $min ? number_format($min) . ' - ' . number_format($max) : number_format($max);
        } elseif (count($prices) == 1) {
            return number_format($prices[0]);
        } else {
            return 0;
        }
    }

    public function getMinPriceAttribute()
    {
        $prices = [];
        $prices = $this->skus->map(function ($sku) {
            return $sku->price;
        });

        $min_price = 0;

        if (count($prices) > 1) {
            $ary = [];
            foreach ($prices as $price) {
                array_push($ary, $price);
            }

            $min_price = min($ary);
        } elseif (count($prices) == 1) {
            $min_price = $prices[0];
        }
        return $min_price;
    }

    public function getPriceRole()
    {
        return  auth()->check() && !in_array(auth()->user()->role->slug, ['admin', 'technician', 'manager', 'operator', 'owner']) ? auth()->user()->role : Role::where('slug', 'customer')->first();
    }

    public function getDiscount()
    {
        $discount = $this->discounts()->whereNull('expired')->orWhereDate('expired', '>=', now())->first();

        return $discount ? $discount : '';
    }

    public function getDiscountAttribute()
    {
        $role = $this->getPriceRole();

        $discount = $role->discounts()
            ->whereHas('items', function ($q) {
                $q->where('item_id', $this->id);
            })->latest()->first();


        $discount = $discount && ($discount->expired == null || \Carbon\Carbon::parse($discount->expired) >= \Carbon\Carbon::now()) ? $discount : null;


        if ($discount !== null) {
            $many_prices = strpos($this->price, ' - ');
            if ($many_prices > 1) {
                //10000 - 14000 to  9000 - 12600 discount (10%)
                $exp_prices = explode(" - ", $this->price);

                $discount_price = "";
                foreach ($exp_prices as $index => $exp_price) {

                    $discount_amt = $discount->status->slug == 'percent' ? (filter_var($exp_price, FILTER_SANITIZE_NUMBER_INT) * $discount->amt) / 100 : $discount->amt;

                    $discount_amt = filter_var($exp_price, FILTER_SANITIZE_NUMBER_INT) - $discount_amt;

                    $discount_price .= $index !== 0 ? ' - ' : '';

                    $discount_price .= number_format($discount_amt);
                }
                return $discount_price;
            } else {
                //40000 to 36000 discount (10%)
                $discount_amt = $discount->status->slug == 'percent' ? (filter_var($this->price, FILTER_SANITIZE_NUMBER_INT) * $discount->amt) / 100 : $discount->amt;

                $discount_amt = filter_var($this->price, FILTER_SANITIZE_NUMBER_INT) - $discount_amt;

                return number_format($discount_amt);
            }
        }
        return 0;
    }

    public function getStock()
    {
        return $this->skus->reduce(function($total, $sku){
            return $total + $sku->stock;
        },0);
    }


    public function getSingleDiscount($price)
    {
        $role = $this->getPriceRole();

        $discount = $role->discounts()->whereHas('items', function ($q) {
            $q->where('item_id', $this->id);
        })->latest()->first();

        if ($discount != null) {
            $discount_amt = $discount->status->slug == 'percent' ? ($price * $discount->amt) / 100 : $discount->amt;

            return $price - $discount_amt;
        }
        return 0;
    }

    public function getImagesAttribute()
    {
        $images = [];

        foreach ($this->medias as $media) {
            array_push($images, $media);
        }

        foreach ($this->skus as $sku) {
            foreach ($sku->medias as $media) {
                array_push($images, $media);
            }
        }

        return $images;
    }

    public function isCurrency($id)
    {
        return $this->currency_id === $id;
    }

    public function isBrand($id)
    {
        foreach ($this->brands as $brand) {
            if ($brand->id == $id) {
                return true;
            }
        }
        return false;
    }

    public function isType($id)
    {
        foreach ($this->types as $type) {
            if ($type->id == $id) {
                return true;
            }
        }
        return false;
    }

    public function hasInventory()
    {
        $boo = false;

        foreach ($this->skus as $sku) {
            if ($sku->inventories->count()) {
                $boo = true;
            }
        }

        return $boo;
    }

    public function hasOrder()
    {
        $boo = false;

        foreach ($this->skus as $sku) {
            if ($sku->orders->count()) {
                $boo = true;
            }
        }

        return $boo;
    }

    public function getLatestExchangeRate()
    {
        $rate = 1;

        $sku = $this->skus()->first();

        if ($sku && $sku->currency->exchangerate()) {
            $rate = $sku->currency->exchangerate()->mmk;
        }

        return $rate;
    }

    public function isOutOfStock()
    {
        $stock_count = 0;

        foreach ($this->skus as $sku) {
            if ($sku->stock == 0) {
                $stock_count++;
            }
        }
        return $stock_count == $this->skus->count();
    }

    //scope functions

    public function scopeIsTypeId($query, $id)
    {
        $query->whereHas('types', function ($q) use ($id) {
            $q->where('type_id', $id);
        });
    }

    public function scopeMainType($query, $name)
    {
        if ($name) {
            $query->whereHas('types', function ($q) use ($name) {
                $q->whereHas('maintypes', function ($q) use ($name) {
                    $q->where('slug', $name);
                });
            });
        }
    }

    public function scopeFilterOnType($query, $name)
    {
        if ($name) {
            $query->whereHas('types', function ($q) use ($name) {
                $q->where('slug', $name);
            });
        }
    }

    public function scopeIsDiscountType($query, $id)
    {
        $query->whereHas('discounts', function ($query) use ($id) {
            $query->whereHas('discountype', function ($query) use ($id) {
                $query->where('id', $id)
                    ->orWhere('slug', $id);
            });
        });
    }

    public function scopeIsBrandId($query, $id)
    {
        $query->whereHas('brands', function ($q) use ($id) {
            $q->where('slug', $id);
        });
    }

    public function scopeDiscountItem($query)
    {
        $query->whereHas('discounts', function ($q) {
            $q->where('amt', '>', 0);
        });
    }

    public function scopeFilterItems($query)
    {
        if (auth()->user()->role->slug == 'admin') {

            $query->whereDoesntHave('pricings');
        }
    }

    public function scopeEnabledItem($query)
    {
        $query->where('disabled', 0)
            ->whereHas('types', function ($q) {
                $q->where('disabled', 0)
                    ->whereHas('maintypes', function ($q) {
                        $q->where('disabled', 0);
                    });
            });
    }

    public function scopeNotDisabled($query)
    {
        $query->where('disabled', 0)
            ->whereHas('types', function ($q) {
                $q->where('disabled', 0)
                    ->whereHas('maintypes', function ($q) {
                        $q->where('disabled', 0);
                    });
            });
    }

    public function scopeFilterOnPrice($query)
    {
        $query->whereHas('skus', function ($query) {
            $query->whereHas('pricings', function ($query) {
                $query->where('amt', '>', 0);
            });
        });
    }

    public function scopeFilterOnDiscountType($query, $type)
    {
        $query->whereHas('discounts', function ($query) use ($type) {
            $query->whereHas('discountype', function ($query) use ($type) {
                $query->where('id', $type)->orWhere('slug', $type);
            });
        });
    }

    public function scopeFilterOn($query)
    {
        if (request('type')) {
            $type = request('type');
            $query->whereHas('types', function ($q) use ($type) {
                $q->where('id', $type)->orWhere('slug', $type);
            });
        }

        if (request('maintype')) {
            $query->whereHas('types', function ($q) {
                $q->whereHas('maintypes', function ($q) {
                    $q->where('id', request('maintype'))->orWhere('slug', request('maintype'));
                });
            });
        }

        if (request('q')) {
            $query->where('name', 'like', '%' . request('q') . '%')
                ->orWhere('code', request('q'))
                ->orWhereHas('skus', function ($query) {
                    $query->where('data', 'like', '%' . request('q') . '%')
                        ->orWhere('code', 'like', '%' . request('q') . '%');
                });
        }

        if (request('brand')) {
            $query->whereHas('brand', function ($query) {
                $query->where('slug', request('brand'));
            });
        }

        if (request('disabled') && request('disabled') == 'disabled') {
            $query->where('disabled', 1);
        } else {
            $query->where('disabled', 0);
        }

        if (request('discountype')) {
            $query->whereHas('discounts', function ($query) {
                $query->whereHas('discountype', function ($query) {
                    $query->where('slug', request('discountype'));
                });
            });
        }

        if(request('status')) {
            switch(request('status')) {
                case 'disabled':
                    $query->where('disabled', 1);
                    break;
                case 'trashed':
                    $query->whereNotNull('deleted_at');
                    break;
            }
        }

        if (request('pricing') ==  'zero') {
            $query->whereHas('skus', function ($query) {
                $query->whereHas('pricings', function ($query) {
                    $query->where('amt', 0);
                })->orWhereDoesntHave('pricings');
            });
        }

        if(request('unit')) {
            $query->whereHas('unit', function($query) {
                $query->where('id', request('unit'));
            });
        }
    }
}
