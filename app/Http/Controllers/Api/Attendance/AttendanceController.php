<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\MonthlyAttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function getMonthlyAttedance(MonthlyAttendanceRequest $request): JsonResponse
    {
        // Ambil data absensi berdasarkan request user.
        $monthlyAttendance = Attendance::where('user_id', $request->user()->id)
            ->whereMonth('date', $request->month)
            ->whereYear('date', $request->year)
            ->orderBy('date')
            ->get();

        // Return response List.
        return response()->json([
            'error'   => false,
            'message' => 'Get monthly attendance successfully.',
            'data'    => AttendanceResource::collection($monthlyAttendance),
        ]);
    }

    public function checkIn(Request $request): JsonResponse
    {
        // Ambil user yang login.
        $user = $request->user();

        // Cek apakah user ini sudah check-in hari ini.
        $hasAttendToday = Attendance::where('user_id', $user->id)
            ->where('date', today())
            ->first();

        // Kalau sudah check-in, tolak.
        if ($hasAttendToday) {
            return response()->json([
                'error'   => true,
                'message' => 'You have already checked in today.',
            ], 409);
        }

        // Tentukan status berdasarkan jam check-in.
        $checkIn = Carbon::now();
        $status = $checkIn->format('H:i') > '07:30' ? 'terlambat' : 'hadir';

        // Simpan attendance.
        $attendance = Attendance::create([
            'user_id'  => $user->id,
            'date'     => today(),
            'check_in' => $checkIn,
            'status'   => $status,
        ]);

        // Return response.
        return response()->json([
            'error'   => false,
            'message' => 'Check in successfully.',
            'data'    => new AttendanceResource($attendance),
        ]);
    }

    public function checkOut(Request $request): JsonResponse
    {
        $user = $request->user();

        // Cek apakah user ada data absensi hari ini.
        $hasAttendToday = Attendance::where('user_id', $user->id)
            ->where('date', today())
            ->first();

        // Jika tidak ada absensi hari ini, tetapi user melakukan check_out,
        // tetap input absensi tetapi input check_out saja.
        // ini diperlukan untuk memberi tahu user untuk melaporkan bahwa hari ini dia tidak atau
        // lupa untuk melakukan check in (fingerprint) di pagi hari.

        // Logic ini akan sangat membantu user, agar user tahu bahwa ia lupa melakukan check_in di pagi hari,
        // dan di flutter bisa menambah logic untuk memunculkan notifikasi hal ini.
        if (! $hasAttendToday) {
            $attendance = Attendance::create([
                'user_id'   => $user->id,
                'date'      => today(),
                'check_out' => Carbon::now(),
                'status'    => 'lupa_checkin',
            ]);

            return response()->json([
                'error'   => false,
                'message' => 'Check out successfully. But you forgot to check in this morning.',
                'data'    => new AttendanceResource($attendance),
            ], 201);
        }

        // Check apakah user sudah checkout.
        if ($hasAttendToday->check_out) {
            return response()->json([
                'error'   => true,
                'message' => 'You have already checked out today.',
            ], 409);
        }

        // Update check-out.
        $hasAttendToday->update([
            'check_out' => Carbon::now(),
        ]);

        $attendance = $hasAttendToday->fresh();

        return response()->json([
            'error'   => false,
            'message' => 'Check out successfully.',
            'data'    => new AttendanceResource($attendance),
        ]);
    }
}
