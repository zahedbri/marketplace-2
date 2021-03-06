<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * JSON resource for additional message data
 *
 * @package App\Http\Resources
 */
class MessageAdditional extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        /** @var MessageAdditional|\App\Message $this */

        return [
            'offer' => $this->when($this->offer, new Offer($this->offer)),
        ];
    }
}
