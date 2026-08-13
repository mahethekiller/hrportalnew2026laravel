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
     * Display the separate admin management page with delete permissions (Password Protected: 254032).
     */
    public function admin(Request $request)
    {
        if (!session('tekken_admin_auth')) {
            return view('tekken.admin_login');
        }

        $registrations = TekkenRegistration::orderBy('created_at', 'asc')->get();

        // Calculate duplicate counts per IP and Hostname for anti-fraud alerts
        $ipCounts = $registrations->groupBy('ip_address')->map->count();
        $hostnameCounts = $registrations->groupBy('device_name')->map->count();

        $stats = [
            'total_players' => $registrations->count(),
            'in_queue' => $registrations->where('status', 'in_queue')->count(),
            'playing' => $registrations->where('status', 'playing')->count(),
            'completed' => $registrations->where('status', 'completed')->count(),
            'total_fees' => $registrations->sum('fee_paid'),
        ];

        return view('tekken.admin', compact('registrations', 'stats', 'ipCounts', 'hostnameCounts'));
    }

    /**
     * Verify Admin Security PIN Code (254032).
     */
    public function verifyAdminPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string',
        ]);

        if (trim($request->input('pin')) === '254032') {
            session(['tekken_admin_auth' => true]);
            return redirect()->route('tekken.admin')->with('success', 'ACCESS GRANTED! Welcome Admin.');
        }

        return redirect()->route('tekken.admin')->with('error', 'ACCESS DENIED! Invalid Security Code.');
    }

    /**
     * Lock/Logout Admin session.
     */
    public function logoutAdmin()
    {
        session()->forget('tekken_admin_auth');
        return redirect()->route('tekken.admin')->with('success', 'Admin session locked.');
    }

    /**
     * Handle public registration submission with Device Hostname & IP tracking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'festive_green' => 'nullable',
            'matches' => 'required|integer|min:1|max:100',
            'utr_number' => 'required|string|max:100|unique:tekken_registrations,utr_number',
        ], [
            'utr_number.unique' => 'This UTR / Payment Reference ID has already been registered! Please check your receipt.',
        ]);

        $matches = (int) $validated['matches'];
        $feePaid = $matches * 30.00;
        $festiveGreen = filter_var($request->input('festive_green', false), FILTER_VALIDATE_BOOLEAN);

        $clientIp = $request->ip() ?: '127.0.0.1';
        $deviceName = $this->resolveDeviceHostname($clientIp);
        $macAddress = $this->resolveMacAddress($clientIp);
        $deviceHash = substr(trim($request->input('device_hash', '')), 0, 100);
        $userAgent = substr($request->header('User-Agent', ''), 0, 500);

        $registration = TekkenRegistration::create([
            'full_name' => trim($validated['full_name']),
            'department' => trim($validated['department']),
            'festive_green' => $festiveGreen,
            'matches' => $matches,
            'fee_paid' => $feePaid,
            'utr_number' => trim($validated['utr_number']),
            'status' => 'in_queue',
            'ip_address' => $clientIp,
            'mac_address' => $macAddress,
            'device_name' => $deviceName,
            'device_hash' => $deviceHash,
            'user_agent' => $userAgent,
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
                    'ip_address' => $registration->ip_address,
                    'mac_address' => $registration->mac_address,
                    'device_name' => $registration->device_name,
                    'time' => $registration->created_at->format('h:i A'),
                ]
            ]);
        }

        return redirect()->route('tekken.index')->with('success', 'Registration submitted successfully!');
    }

    /**
     * Resolve Computer Hostname via Reverse DNS & NetBIOS.
     */
    protected function resolveDeviceHostname(string $ip): string
    {
        if (in_array($ip, ['127.0.0.1', '::1'])) {
            return gethostname() ?: 'LOCAL-HOST';
        }

        $hostname = @gethostbyaddr($ip);
        if ($hostname && $hostname !== $ip) {
            return $hostname;
        }

        try {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $output = shell_exec("nbtstat -A " . escapeshellarg($ip));
                if ($output && preg_match('/^\s*([A-Za-z0-9\-_]+)\s+<00>\s+UNIQUE/m', $output, $matches)) {
                    return trim($matches[1]);
                }
            }
        } catch (\Throwable $e) {
            // Ignore execution failures
        }

        return 'CLIENT-' . substr(md5($ip), 0, 6);
    }

    /**
     * Resolve MAC Address via Windows ARP Table.
     */
    protected function resolveMacAddress(string $ip): string
    {
        if (in_array($ip, ['127.0.0.1', '::1'])) {
            return 'LOCAL-INTERFACE';
        }

        try {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $output = shell_exec("arp -a " . escapeshellarg($ip));
                if ($output && preg_match('/([0-9a-fA-F]{2}[:-][0-9a-fA-F]{2}[:-][0-9a-fA-F]{2}[:-][0-9a-fA-F]{2}[:-][0-9a-fA-F]{2}[:-][0-9a-fA-F]{2})/', $output, $matches)) {
                    return strtoupper(str_replace('-', ':', $matches[1]));
                }
            }
        } catch (\Throwable $e) {
            // Ignore execution failures
        }

        return 'UNKNOWN-MAC';
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
     * Export all registrations as CSV with Device & Network info.
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
            fputcsv($file, ['ID', 'Player Name', 'Department', 'Festive Green', 'Matches', 'Fee Paid', 'UTR Number', 'Computer Hostname', 'IP Address', 'MAC Address', 'Device Hash', 'Status', 'Registered At']);

            foreach ($registrations as $reg) {
                fputcsv($file, [
                    $reg->id,
                    $reg->full_name,
                    $reg->department,
                    $reg->festive_green ? 'YES' : 'NO',
                    $reg->matches,
                    $reg->fee_paid,
                    $reg->utr_number,
                    $reg->device_name ?: 'N/A',
                    $reg->ip_address ?: 'N/A',
                    $reg->mac_address ?: 'N/A',
                    $reg->device_hash ?: 'N/A',
                    strtoupper(str_replace('_', ' ', $reg->status)),
                    $reg->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
