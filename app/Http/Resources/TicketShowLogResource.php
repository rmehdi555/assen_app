<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketShowLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($this->file_id==null){
            $file=false;
        }else{
            $file=false;
        }
        return [
            'content' => $this->content,
            'status_name' => $this->status->name,
            'department_name' => $this->department->name,
            'agent_name' => $this->agent->name ?? '',
            'admin' => ($this->agent==null)? false:true,
            'created_at'=>showDate($this->created_at),
            'file'=>$file
        ];
    }
}
