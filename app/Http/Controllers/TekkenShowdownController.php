<?php

namespace App\Http\Controllers;

use App\Models\TekkenRegistration;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TekkenShowdownController extends Controller
{
    /**
     * Display the public standalone TEKKEN 7 Showdown page.
     */
    public function index()
    {
        $registrations = TekkenRegistration::orderBy('created_at', 'asc')->get();

        $stats = [
            'total_players' => $registrations->count(),
            'in_queue' => $registrations->where('status', 'in_queue')->count(),
            'playing' => $registrations->where('status', 'playing')->count(),
            'completed' => $registrations->where('status', 'completed')->count(),
            'total_fees' => $registrations->sum('fee_paid'),
        ];

        return view('tekken.showdown', compact('registrations', 'stats'));
    }

    /**
     * Display the separate admin management page with delete permissions.
     */
    public function admin()
    {
        $registrations = TekkenRegistration::orderBy('created_at', 'asc')->get();

        $stats = [
            'total_players' => $registrations->count(),
            'in_queue' => $registrations->where('status', 'in_queue')->count(),
            'playing' => $registrations->where('status', 'playing')->count(),
            'completed' => $registrations->where('status', 'completed')->count(),
            'total_fees' => $registrations->sum('fee_paid'),
        ];

        return view('tekken.admin', compact('registrations', 'stats'));
    }

    /**
     * Handle public registration submission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'festive_green' => 'nullable',
            'matches' => 'required|integer|min:1|max:100',
            'utr_number' => 'required|string|max:100',
        ]);

        $matches = (int) $validated['matches'];
        $feePaid = $matches * 30.00;
        $festiveGreen = filter_var($request->input('festive_green', false), FILTER_VALIDATE_BOOLEAN);

        $registration = TekkenRegistration::create([
            'full_name' => trim($validated['full_name']),
            'department' => trim($validated['department']),
            'festive_green' => $festiveGreen,
            'matches' => $matches,
            'fee_paid' => $feePaid,
            'utr_number' => trim($validated['utr_number']),
            'status' => 'in_queue',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'FIGHTER REGISTERED! PREPARE FOR BATTLE!',
                'data' => [
                    'id' => $registration->id,
                    'full_name' => $registration->full_name,
                    'department' => $registration->department,
                    'festive_green' => $registration->festive_green,
                    'matches' => $registration->matches,
                    'fee_paid' => number_format($registration->fee_paid, 2),
                    'utr_number' => $registration->utr_number,
                    'status' => $registration->status,
                    'status_label' => $registration->status_label,
                    'status_badge_class' => $registration->status_badge_class,
                    'time' => $registration->created_at->format('h:i A'),
                ]
            ]);
        }

        return redirect()->route('tekken.index')->with('success', 'Registration submitted successfully!');
    }

    /**
     * Cycle or update match status (Public / Unauthenticated).
     */
    public function updateStatus(Request $request, $id)
    {
        $registration = TekkenRegistration::findOrFail($id);

        if ($request->has('status')) {
            $registration->status = $request->input('status');
        } else {
            // Cycle through statuses
            $registration->status = match ($registration->status) {
                'in_queue' => 'playing',
                'playing' => 'completed',
                'completed' => 'in_queue',
                default => 'in_queue',
            };
        }

        $registration->save();

        return response()->json([
            'success' => true,
            'status' => $registration->status,
            'status_label' => $registration->status_label,
            'badge_class' => $registration->status_badge_class,
        ]);
    }

    /**
     * Remove a player registration.
     */
    public function destroy($id)
    {
        $registration = TekkenRegistration::findOrFail($id);
        $registration->delete();

        return response()->json([
            'success' => true,
            'message' => 'Fighter removed from tournament queue.'
        ]);
    }

    /**
     * Export all registrations as CSV.
     */
    public function export(): StreamedResponse
    {
        $fileName = 'tekken7_registrations_' . date('Y_m_d_His') . '.csv';
        $registrations = TekkenRegistration::orderBy('created_at', 'asc')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($registrations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Player Name', 'Department', 'Festive Green (T-Shirt)', 'Matches', 'Fee Paid (INR)', 'UTR Number', 'Status', 'Registered At']);

            foreach ($registrations as $reg) {
                fputcsv($file, [
                    $reg->id,
                    $reg->full_name,
                    $reg->department,
                    $reg->festive_green ? 'YES' : 'NO',
                    $reg->matches,
                    $reg->fee_paid,
                    $reg->utr_number,
                    strtoupper(str_replace('_', ' ', $reg->status)),
                    $reg->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
