<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    /**
     * Export data to CSV
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function csv(Request $request)
    {
        $data = $request->input('data', []);
        $filename = $request->input('filename', 'export_' . date('Y-m-d_His') . '.csv');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            if (!empty($data)) {
                // Write headers
                if (isset($data[0]) && is_array($data[0])) {
                    fputcsv($file, array_keys($data[0]));
                }
                
                // Write data rows
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
            }
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export data to JSON
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function json(Request $request)
    {
        $data = $request->input('data', []);
        $filename = $request->input('filename', 'export_' . date('Y-m-d_His') . '.json');

        return response()->json($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}

