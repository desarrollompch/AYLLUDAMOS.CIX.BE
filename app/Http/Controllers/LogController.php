<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Excel;
use Carbon\Carbon;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Activity $activity)
    {
        $this->authorize('view', [$activity, 'Activity']);

        return view('log.index');
    }

    public function exportToExcel(Request $request)
    {
        $user_id = $request->get('user-id');

        $date = $request->get('date');

        if(!empty($date))
        {
            $date = Carbon::createFromFormat('d/m/Y', $date);
            $date = $date->format('Y-m-d');
        }

        $activities = Activity::with('causer')
            ->where('causer_id', 'like', '%'.$user_id.'%')
            ->whereDate('created_at', 'like', '%'.$date.'%')
            ->orderBy('created_at', 'DESC')
            ->get();

        $filename = "log-".now();

        Excel::create($filename, function($excel) use($activities){
            $excel->sheet("Datos", function($sheet) use($activities){
                $sheet->mergeCells("A1:F1");
                $sheet->cell("A1", function($cell) {
                    $cell->setValue("Listado de Transacciones");
                    $cell->setAlignment("center");
                    $cell->setFont(array("family" => "Calibri", "size"=>14, "bold" => true));
                });
                $sheet->cell("A2", function($cell) {$cell->setValue("ID"); $cell->setAlignment("center"); });
                $sheet->cell("B2", function($cell) {$cell->setValue("Fecha"); $cell->setAlignment("center"); });
                $sheet->cell("C2", function($cell) {$cell->setValue("Acción"); $cell->setAlignment("center"); });
                $sheet->cell("D2", function($cell) {$cell->setValue("Usuario"); $cell->setAlignment("center"); });
                if(!empty($activities)) {
                    foreach($activities as $key => $value) {
                        $causer = $value->causer;
                        $usuario = $causer['name'];
                        if(isset($causer['email'])) {
                            $usuario .= " - ".$causer['email'];
                        }
                        $i = $key + 3;
                        $sheet->cell('A'.$i, $value->id);
                        $sheet->cell('B'.$i, $value->created_at);
                        $sheet->cell('C'.$i, $value->description);
                        $sheet->cell('D'.$i, $usuario);
                    }
                }
            });
        })->export('xlsx');
    }


    public function all(Activity $activity, Request $request)
    {
        $this->authorize('view', [$activity, 'Activity']);

        $user_id = $request->get('user_id');
        $date = $request->get('date');

        if(!empty($date))
        {
            $date = Carbon::createFromFormat('d/m/Y', $date);
            $date = $date->format('Y-m-d');
        }

        $activities = Activity::with('causer')
            ->where('causer_id', 'like', '%'.$user_id.'%')
            ->whereDate('created_at','like', '%'.$date.'%')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);


        return ['success' => true, 'activities' => $activities];
    }



}
