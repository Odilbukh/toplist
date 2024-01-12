<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{

//    private int $index;
    private bool $isSelf;

    public function __construct($resource,  bool $isSelf = false)
    {
        parent::__construct($resource);
//        $this->index = $index;
        $this->isSelf = $isSelf;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'email' =>  $this->member->hidden_email ?? null,
            'place' => $this->when(isset($this->resource->place), $this->resource->place),
            'milliseconds' => $this->milliseconds,
        ];
    }
}
