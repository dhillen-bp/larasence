<?php

namespace App\Exports;

use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendancesExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $attendances = Attendance::with('user')->get();

        $resources = AttendanceResource::collection($attendances);

        return $resources->map(function ($item) {
            $data = $item->toArray(request());

            return [
                'Name' => $data['user']['name'] ?? '-',
                'Email' => $data['user']['email'] ?? '-',
                'Check In' => $data['check_in'] ?? '-',
                'Check Out' => $data['check_out'] ?? '-',
                'Status' => $data['formatted_status'] ?? '-',
            ];
        });
    }


    public function headings(): array
    {
        return ['Name', 'Email', 'Check In', 'Check Out', 'Status'];
    }
}
