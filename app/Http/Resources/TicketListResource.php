<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $status_id=$this->ticketStatus->id;
        if ($status_id==4 or $status_id==7){
            $log=$this->logs;
            $res_date=$log[0]->created_at;
            $res_date=showDate($res_date);
            if($status_id==7){
                $is_active_response=false;
            }else{
                $is_active_response=true;
            }
        }
        else{
            $res_date=null;
            $is_active_response=true;

        }
        return array(
            'code' => $this->code,
            'title' => $this->title,
            'status_id' => $this->ticketStatus->name,
            'created_at' => showDate($this->created_at),
            'department_id' => $this->department->name,
            'res_date'=>$res_date,
            'is_response' => ($this->ticketStatus->id==4) ? true : false,
            'invoice_code'=>$this->invoice->code,
            'is_active_response'=>$is_active_response
        );
    }
}
