<?php

namespace App\Http\Controllers;

use App\Models\LaptopReservation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LaptopReservationController
{
    public function index(): JsonResponse
    {
        return response()->json(LaptopReservation::latest()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'requester_name'      => ['required', 'string', 'max:255'],
            'laptop_asset_number' => ['required', 'string', 'max:100'],
            'student_class'       => ['required', 'string', 'max:100'],
            'teacher_name'        => ['required', 'string', 'max:255'],
            'subject'             => ['required', 'string', 'max:255'],
            'includes_charger'    => ['boolean'],
            'charger_code'        => ['nullable', 'required_if:includes_charger,true', 'string', 'max:100'],
        ]);

        $reservation = LaptopReservation::create($validated);

        return response()->json($reservation, 201);
    }

    public function update(Request $request, LaptopReservation $reservation): JsonResponse
    {
        $validated = $request->validate([
            'requester_name'      => ['required', 'string', 'max:255'],
            'laptop_asset_number' => ['required', 'string', 'max:100'],
            'student_class'       => ['required', 'string', 'max:100'],
            'teacher_name'        => ['required', 'string', 'max:255'],
            'subject'             => ['required', 'string', 'max:255'],
            'includes_charger'    => ['boolean'],
            'charger_code'        => ['nullable', 'required_if:includes_charger,true', 'string', 'max:100'],
        ]);

        if (!$validated['includes_charger']) {
            $validated['charger_code'] = null;
        }

        $reservation->update($validated);

        return response()->json($reservation);
    }

    public function destroy(LaptopReservation $reservation): JsonResponse
    {
        $reservation->delete();

        return response()->json(['message' => 'Reserva excluída com sucesso.']);
    }
}