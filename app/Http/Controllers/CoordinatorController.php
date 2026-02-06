<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Reviews;
use App\Models\User;
use App\Models\Event;
use App\Models\Coordinator;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CoordinatorController extends Controller
{
    // ---------------- Helper methods ----------------
    private function coordinatorIdOrNull(): ?int
    {
        $coordinator = Auth::user()?->coordinator;
        return $coordinator ? (int) $coordinator->id : null;
    }

    private function requireCoordinatorId(): int
    {
        $id = $this->coordinatorIdOrNull();
        if (!$id) abort(403, 'Coordinator profile not found.');
        return $id;
    }

    // ---------------- DASHBOARD ----------------
    public function dashboard()
    {
        $user = Auth::user();

        // CHECK: If user is coordinator but has no profile row, create it now.
        if ($user->role === 'coordinator' && !$user->coordinator) {
             Coordinator::create([
                'user_id' => $user->id,
                'coordinator_name' => $user->name,
                'expertise' => '',
                'phone_number' => '',
                'address' => '',
                'status' => 'approved', 
            ]);
            $user->refresh(); // Reload user relationship
        }

        $coordinatorId = $this->coordinatorIdOrNull();

        // Safety check if creation failed or user is not a coordinator
        if (!$coordinatorId) {
            return view('coordinator.dashboard', [
                'pendingBookings' => collect(),
                'stats' => [],
                'statusChart' => [],
                'activityLabels' => [],
                'activityData' => []
            ]);
        }

        $pendingBookings = Booking::where('coordinator_id',$coordinatorId)
            ->where('status','pending')->with('client')->get();
        $confirmedBookings = Booking::where('coordinator_id',$coordinatorId)->where('status','confirmed')->count();
        $upcomingEvents = Booking::where('coordinator_id',$coordinatorId)->whereDate('event_date','>=',now())->count();

        $stats = [
            ['label'=>'Confirmed Bookings','value'=>$confirmedBookings,'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m6-4a4 4 0 11-8 0 4 4 0 018 0z"/>','link'=>route('coordinator.bookings',['status'=>'confirmed'])],
            ['label'=>'Pending Bookings','value'=>$pendingBookings->count(),'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>','link'=>route('coordinator.bookings',['status'=>'pending'])],
            ['label'=>'Upcoming Events','value'=>$upcomingEvents,'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>','link'=>route('coordinator.schedule')],
        ];

        $statusChart = [
            'completed'=>$confirmedBookings,
            'pending'=>$pendingBookings->count(),
            'cancelled'=>Booking::where('coordinator_id',$coordinatorId)->where('status','cancelled')->count()
        ];

        $activityLabels=[]; $activityData=[];
        for ($i=6;$i>=0;$i--) {
            $date=now()->subDays($i);
            $activityLabels[]=$date->format('D');
            $activityData[]=Booking::whereDate('event_date',$date)->where('coordinator_id',$coordinatorId)->count();
        }

        return view('coordinator.dashboard', compact('pendingBookings','stats','statusChart','activityLabels','activityData'));
    }

    // ---------------- BOOKINGS ----------------
    public function bookings(Request $request)
    {
        $coordinatorId = $this->coordinatorIdOrNull();
        if (!$coordinatorId) {
            return redirect()->route('coordinator.profile')
                ->with('error','Coordinator profile not found. Please complete your profile first.');
        }

        $query = Booking::where('coordinator_id',$coordinatorId)->with(['client','event']);
        if ($request->status) $query->where('status',$request->status);
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search){
                $q->whereHas('client', fn($c)=>$c->where('name','like',"%$search%"))
                  ->orWhere('event_name','like',"%$search%");
            });
        }

        $orderBy = $request->get('orderBy','event_date');
        $bookings = $query->orderBy($orderBy,'desc')->paginate(10);
        return view('coordinator.bookings', compact('bookings'));
    }

    public function bookingsShow($id)
    {
        $coordinatorId = $this->requireCoordinatorId();
        $booking = Booking::with(['client','event','coordinator.user'])
            ->where('coordinator_id',$coordinatorId)->findOrFail($id);
        return view('coordinator.bookings-show', compact('booking'));
    }

    public function updateBooking(Request $request, $id)
    {
        $coordinatorId = $this->requireCoordinatorId();
        $booking = Booking::where('coordinator_id',$coordinatorId)->findOrFail($id);
        $request->validate(['status'=>'required|in:pending,confirmed,cancelled']);
        $booking->status = $request->status;
        $booking->save();
        return back()->with('success',"Booking {$request->status} successfully!");
    }

    public function confirmBooking($id)
    {
        request()->merge(['status'=>'confirmed']);
        return $this->updateBooking(request(), $id);
    }

    public function cancelBooking($id)
    {
        request()->merge(['status'=>'cancelled']);
        return $this->updateBooking(request(), $id);
    }

    // ---------------- SCHEDULE (UPDATED) ----------------
    public function schedule(Request $request) 
    { 
        // 1. Get Coordinator
        $coordinatorId = $this->requireCoordinatorId();

        // 2. Determine Month (from URL or Current)
        $date = $request->has('month') 
                ? Carbon::parse($request->input('month')) 
                : Carbon::now();

        // 3. Calculate Calendar Variables
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth   = $date->copy()->endOfMonth();
        
        // Days to skip before 1st of month (0=Sun, 1=Mon...)
        $emptySlots = $startOfMonth->dayOfWeek; 
        
        // Total days in month
        $daysInMonth = $date->daysInMonth;

        // 4. Navigation Links
        $prevMonth = $date->copy()->subMonth()->format('Y-m-d');
        $nextMonth = $date->copy()->addMonth()->format('Y-m-d');

        // 5. Fetch Busy Dates
        
        // Client Bookings
        $clientBookings = Booking::where('coordinator_id', $coordinatorId)
            ->whereIn('status', ['confirmed', 'paid', 'pending']) 
            ->whereBetween('event_date', [$startOfMonth, $endOfMonth])
            ->pluck('event_date')
            ->map(fn($d) => Carbon::parse($d)->format('j')) // Get day number (no leading zero)
            ->toArray();

        // Personal Schedules
        $personalSchedules = Schedule::where('coordinator_id', $coordinatorId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->format('j'))
            ->toArray();

        // Merge and clean
        $bookedDates = array_unique(array_merge($clientBookings, $personalSchedules));

        return view('coordinator.schedule', compact(
            'date', 
            'emptySlots', 
            'daysInMonth', 
            'prevMonth', 
            'nextMonth', 
            'bookedDates'
        ));
    }

    public function getScheduleEvents()
    {
        $events=[]; 
        $coorId = $this->coordinatorIdOrNull();

        if($coorId) {
            $bookings = Booking::with('client')->where('coordinator_id',$coorId)->where('status','!=','cancelled')->get();
            foreach($bookings as $b){
                $events[]=[
                    'id'=>'booking-'.$b->id,
                    'title'=>'Client: '.($b->client->name??'Booking'),
                    'start'=>$b->booking_date,
                    'backgroundColor'=>'#3E3F29',
                    'borderColor'=>'#3E3F29',
                    'extendedProps'=>['location'=>$b->location??'TBD','status'=>ucfirst($b->status),'type'=>'Client Booking']
                ];
            }
            
            try {
                $schedules = Schedule::where('coordinator_id',$coorId)->get();
                foreach($schedules as $s){
                    $events[]=[
                        'id'=>'schedule-'.$s->id,
                        'title'=>$s->name,
                        'start'=>$s->date.'T'.$s->start_time,
                        'end'=>$s->date.'T'.$s->end_time,
                        'backgroundColor'=>'#A1BC98',
                        'borderColor'=>'#A1BC98',
                        'extendedProps'=>['location'=>$s->location??'','type'=>'Personal Schedule']
                    ];
                }
            } catch (\Exception $e){
                Log::error('Schedule table error: '.$e->getMessage());
            }
        }

        return response()->json($events);
    }

    public function saveEvent(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'date'=>'required|date',
            'start_time'=>'required',
            'end_time'=>'required',
            'location'=>'nullable|string|max:255',
        ]);

        $coorId = $this->requireCoordinatorId();

        try {
            $event = Schedule::create([
                'coordinator_id'=>$coorId, 
                'name'=>$request->name,
                'date'=>$request->date,
                'start_time'=>Carbon::parse($request->start_time)->format('H:i:s'),
                'end_time'=>Carbon::parse($request->end_time)->format('H:i:s'),
                'location'=>$request->location
            ]);

            return response()->json(['success'=>true,'event'=>$event]);
        } catch (\Exception $e){
            Log::error('Save Event Error: '.$e->getMessage());
            return response()->json(['success'=>false,'message'=>'Database Error: '.$e->getMessage()],500);
        }
    }

    // ---------------- REVIEWS ----------------
    public function reviews()
    {
        $coordinatorId = $this->coordinatorIdOrNull();
        if(!$coordinatorId) return redirect()->back()->with('error', 'Profile not found');

        $reviews = Reviews::where('coordinator_id',$coordinatorId)->with('client')->latest()->get();
        $formattedAvg = number_format((float)$reviews->avg('rating'),1);
        $totalReviews = $reviews->count();
        return view('coordinator.ratings', compact('reviews','formattedAvg','totalReviews'));
    }
    // ==========================================
    // 2. ADDED PAYMENTS FUNCTIONS HERE
    // ==========================================
    
    /**
     * Show the payments ledger
     */
/**
     * Show the income and payments ledger
     * This matches your route /coordinator/income
     */
public function income()
{
    $coordinatorId = $this->requireCoordinatorId();

    // Fetch ONLY confirmed bookings for the dropdown
    $pendingBookings = Booking::where('coordinator_id', $coordinatorId)
        ->where('status', 'confirmed') 
        ->with('client', 'payments')
        ->get()
        ->map(function($booking) {
            // Calculate remaining balance dynamically
            $paid = $booking->payments->sum('amount');
            $total = $booking->total_amount ?? $booking->total_price ?? 0;
            $booking->balance = $total - $paid;
            return $booking;
        })
        ->filter(function($booking) {
            // Only show clients who haven't paid in full yet
            return $booking->balance > 0;
        });

    $payments = Payment::whereHas('booking', function($q) use ($coordinatorId) {
        $q->where('coordinator_id', $coordinatorId);
    })->with('booking.client')->latest()->get();

    $totalIncome = $payments->sum('amount');

    return view('coordinator.income', compact('pendingBookings', 'payments', 'totalIncome'));
}
    /**
     * Save a new payment from the modal
     */
    public function storePayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:1',
            'date_paid' => 'required|date',
            'method' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        // Security check: Ensure this booking belongs to this coordinator
        $coordinatorId = $this->requireCoordinatorId();
        $booking = Booking::where('coordinator_id', $coordinatorId)
                          ->where('id', $request->booking_id)
                          ->firstOrFail();

        Payment::create([
            'booking_id' => $booking->id,
            'amount' => $request->amount,
            'date_paid' => $request->date_paid,
            'method' => $request->method,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Payment recorded successfully!');
    }

    // ---------------- PROFILE ----------------
    public function profile()
    {
        $user = Auth::user();
        return view('coordinator.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email,'.$user->id,
            'avatar'=>'nullable|file|max:2048',
            'profile_photo'=>'nullable|image|max:2048',
            'password'=>'nullable|string|min:6|confirmed',
            'services'=>'nullable|array',
            'event_types'=>'nullable|array',
            'rate'=>'nullable|numeric|min:0',
            'is_active'=>'nullable|boolean',
            'bio'=>'nullable|string|max:1000',
            'location'=>'nullable|string|max:255',
            'title'=>'nullable|string|max:255',
        ]);

        $user->name=$request->name;
        $user->email=$request->email;
        $user->phone=$request->phone??$user->phone;
        $user->location=$request->location??$user->location;
        $user->title=$request->title??$user->title;
        $user->bio=$request->bio??$user->bio;
        $user->rate=$request->rate??$user->rate;
        
        $user->services = $request->services?json_encode($request->services):json_encode([]);
        if(Schema::hasColumn('users','event_types')) $user->event_types = $request->event_types??[];

        if($request->password) $user->password = Hash::make($request->password);

        if($request->hasFile('avatar') && $request->file('avatar')->isValid()){
            if($user->avatar && Storage::disk('public')->exists($user->avatar)) Storage::disk('public')->delete($user->avatar);
            $user->avatar = $request->file('avatar')->store('avatars/coordinators','public');
        }

        // Profile photo upload
        if($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()){
            $coordinator = $user->coordinator ?? Coordinator::create([
                'user_id'=>$user->id,
                'coordinator_name'=>$user->name,
                'expertise'=>'',
                'phone_number'=>'',
                'address'=>'',
                'status'=>'approved'
            ]);

            if($coordinator->profile_photo && Storage::disk('public')->exists($coordinator->profile_photo)){
                Storage::disk('public')->delete($coordinator->profile_photo);
            }

            $file = $request->file('profile_photo');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/profile_photos',$filename);
            $coordinator->profile_photo = 'profile_photos/'.$filename;
            $coordinator->save();
        }

        $user->save();
        return redirect()->route('coordinator.profile')->with('success','Profile updated successfully!');
    }

    /**
     * Show all coordinators grouped by event type (for clients/guests)
     */
    public function index()
    {
        $allCoordinators = Coordinator::with(['user', 'events'])
            ->whereHas('user', function($query) {
                $query->where('is_active', 1);
            })
            ->get();

        $coordinators = $allCoordinators->groupBy(function($coordinator) {
            $firstEvent = $coordinator->events->first();
            return $firstEvent ? $firstEvent->event_type : 'others';
        });

        return view('coordinators.index', compact('coordinators'));
    }

    /**
     * Show single coordinator profile
     */
    public function show($id) 
    {
        $eventType = request('event'); 

        $coordinator = Coordinator::with(['user', 'events', 'bookings'])
            ->findOrFail($id);

        if (!$coordinator->user || !$coordinator->user->is_active) {
            abort(403, 'This coordinator is not available.');
        }

        return view('coordinators.show', compact('coordinator', 'eventType'));
    }

    /**
     * Update Coordinator (Admin function)
     */
    public function update(Request $request, $id)
    {
        // Security Check: Only Admins can do this
        if (strtolower(trim(Auth::user()->role)) !== 'admin') {
            abort(403, 'Unauthorized. Only admins can update coordinators here.');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($user->coordinator) {
            $user->coordinator->update([
                 'status' => $request->status ?? $user->coordinator->status,
            ]);
        }

        return redirect()->back()->with('success', 'Coordinator updated successfully.');
    }
    public function checkout()
{
    $user = Auth::user();
    // You can pass specific plans or pricing here later
    return view('coordinator.checkout', compact('user'));
}
    
    
}