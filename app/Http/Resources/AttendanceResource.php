<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'check_in' => $this->check_in ? Carbon::parse($this->check_in)->format('Y-m-d H:i:s') : null,
            'check_out' => $this->check_out ? Carbon::parse($this->check_out)->format('Y-m-d H:i:s') : null,
            'status' => $this->status,
            'user' => UserResource::make($this->user),
            'formatted_status' => $this->formatStatus(),
        ];
    }

    private function formatStatus(): string
    {
        return match ($this->status) {
            'on_time' => 'On Time ⏳',
            'late' => 'Late ⏰',
            'absent' => 'Absent ❌',
            'permission' => 'Permission 🙏',
            default => 'Pending 🟡',
        };
    }
}
