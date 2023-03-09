<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => $this->amount,
            'date' => Carbon::parse($this->date)->format('Y-m-d'),
            'type' => [
                'id' => $this->type_id,
                'name' => $this->type ? $this->type->name : '',
            ],
            'reference_id' => $this->reference_id,
            'supplier' => [
                'id' => $this->supplier_id,
                'name' => $this->supplier ? $this->supplier->name : '',
            ]
        ];
    }
}
