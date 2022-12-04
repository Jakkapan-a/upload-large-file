<?php

namespace App\Http\Controllers;

use App\Models\Events;
use Illuminate\Http\Request;

class FullCalenderController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = Events::whereDate('start', '>=', $request->start)
                ->whereDate('end',   '<=', $request->end)
                ->get(['id', 'title', 'start', 'end']);
            return response()->json($data);
        }

        return view('fullcalender');
    }

    public function ajax(Request $request){
        switch ($request->type){
            case 'add':
                $event = Events::create([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end'   => $request->end
                ]);
                return response()->json($event);
                break;
            case 'update':
                $event = Events::find($request->id)->update([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end'   => $request->end
                ]);
                return response()->json($event);
                break;
            case 'delete':
                $event = Events::find($request->id)->delete();
                return response()->json($event);
                break;
            default:
                break;
        }
        return response()->json($request->all());
    }
}
