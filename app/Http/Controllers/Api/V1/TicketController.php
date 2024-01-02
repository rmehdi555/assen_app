<?php

namespace App\Http\Controllers\Api\V1;

use App\Classes\FileUpload;
use App\Enum\FileCategory;
use App\Helpers\Convertors;
use App\Http\Requests\V1\Ticket\TicketIndexRequest;
use App\Http\Requests\V1\Ticket\TicketReplyRequest;
use App\Http\Requests\V1\Ticket\TicketStoreRequest;
use App\Http\Resources\TicketShowLogResource;
use App\Models\Department;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\TicketStatus;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TicketListResource;

class TicketController extends Controller
{

    public function index(TicketIndexRequest $request): JsonResponse
    {
        $tickets = Ticket::where('user_id', Auth::id())
            ->when(
                isset($request->title),
                fn($q) => $q->where('tickets.title', 'Like', '%' . $request->title . '%')
            )
            ->when(
                isset($request->date),
                fn($q) => $q->where('tickets.created_at', 'Like', '%' . $request->date . '%')
            )
            ->when(
                isset($request->status_id),
                fn($q) => $q->where('tickets.status_id', '=', $request->status_id)
            )
            ->latest()
            ->paginate(config('custom.paginate_count'));
        $status = TicketStatus::select('id', 'name')->get();
        $tickets = TicketListResource::collection($tickets);
        return $this->successResponse([
            'tickets'=>$tickets,
            'status'=>$status
        ] ,'' );
    }

    public function create(): JsonResponse
    {
        $departments=Department::select('id','name')->get();
        $invoice = Invoice::select('code')->where('user_id', Auth::user()->id)->get();
        return $this->successResponse([
            'invoice' => $invoice,
            'departments' => $departments
        ]);

    }

    public function store(TicketStoreRequest $request, FileUpload $fileUpload)
    {
        $code = Convertors::datetocode();
        $invoice = Invoice::select('id')->where('code', $request->invoice_id)->get();
        $ticket = Ticket::create([
            "title" => $request->title,
            "user_id" => Auth::user()->id,
            "status_id" => 1,
            "department_id" => $request->department_id,
            "code" => $code,
            "invoice_id" => $invoice[0]['id']
        ]);
        if (isset($request->file))
            $file = $fileUpload->setKey('file')
                ->setRequest($request)
                ->setCaption('user image ticket | user_id: ' . Auth::id())
                ->setCategory(FileCategory::tickets)
                ->save();
        $file_id = isset($file) ? $file->id : null;
        TicketLog::create([
            "content" => $request->body,
            "ticket_id" => $ticket->id,
            "file_id" => $file_id,
            "department_id" => $request->department_id,
            "status_id" => 1
        ]);
        return $this->successResponse($ticket->id, __('messages.ticket_saved_successfully'));
    }

    public function show($code): JsonResponse
    {
        $ticket = Ticket::where('user_id', Auth::user()->id)
            ->where('code', $code)->with(['ticketStatus'])
            ->first();
        if (!filled($ticket))
            return $this->errorResponse(__('messages.item_not_found'), 404);
        $data=[];
        $data['title']=$ticket->title;
        $data['logs']=$ticket->logs;
        $data['user_name']=$ticket->user->name;
        $data['last_department_name']=$data['logs']->last()->department->name;
        $status_id=$ticket->ticketStatus->id;

        if($status_id==7){
            $is_active_response=false;
        }else{
            $is_active_response=true;
        }
        $data['is_active_response']=$is_active_response;
        $data['logs']=TicketShowLogResource::collection($data['logs']);
        return $this->successResponse($data, '');
    }

    public function reply(TicketReplyRequest $request, FileUpload $fileUpload): JsonResponse
    {
        $ticket = Ticket::where('code', $request->code)->first();
        $ticket->status_id=1;
        if (isset($request->file))
            $file = $fileUpload->setKey('file')
                ->setRequest($request)
                ->setCaption('user image ticket | user_id: ' . Auth::id())
                ->setCategory(FileCategory::tickets)
                ->save();
        $file_id = isset($file) ? $file->id : null;
        $ticketlog = TicketLog::create([
            "content" => $request->body,
            "ticket_id" => $ticket->id,
            "file_id" => $file_id,
            "department_id" => $ticket->department_id,
            "status_id" => 1
        ]);
        $invoice = Invoice::where('id', $ticket->invoice_id)->first();
        return $this->successResponse(['code'=>$ticket->code,'invoice_code'=>$invoice->code], __('messages.ticket_reply_successfully'));
    }
}
