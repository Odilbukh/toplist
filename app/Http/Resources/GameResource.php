<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{

    private int $index;
    private bool $isSelf;

    public function __construct($resource, int $index, bool $isSelf = false)
    {
        parent::__construct($resource);
        $this->index = $index;
        $this->isSelf = $isSelf;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request, int $index = 0): array
    {
        return [
            'email' => $this->isSelf ? $this->member->email : $this->member->hidden_email,
            'place' => $this->index + 1 ?? 0,
            'milliseconds' => $this->milliseconds,
        ];
    }
}
