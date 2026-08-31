<?php
namespace App\Http\Controllers;
use App\Http\Requests\SkipActivitiesRequest;
use App\Models\Activity;
use App\Models\DailySkip;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
class CalendarController extends Controller
{
    public function dashboard(): RedirectResponse { return redirect()->route('calendar.show', now()->toDateString()); }
    public function show(string $date): View
    {
        $selectedDate = CarbonImmutable::parse($date)->startOfDay();
        $month = $selectedDate->startOfMonth(); $user = Auth::user();
        $activities = Activity::where('is_active', true)->orderBy('name')->get();
        $skippedIds = DailySkip::whereBelongsTo($user)->whereDate('date',$selectedDate)->pluck('activity_id')->all();
        $dailySkips = DailySkip::with('activity')->whereBelongsTo($user)->whereDate('date',$selectedDate)->get();
        $monthSkips = DailySkip::select('activity_id', DB::raw('count(*) as total'))->whereBelongsTo($user)->whereBetween('date',[$month->toDateString(),$month->endOfMonth()->toDateString()])->groupBy('activity_id')->pluck('total','activity_id');
        $calendarCounts = DailySkip::select('date', DB::raw('count(*) as total'))->whereBelongsTo($user)->whereBetween('date',[$month->startOfWeek()->toDateString(),$month->endOfMonth()->endOfWeek()->toDateString()])->groupBy('date')->pluck('total','date');
        $daysTracked = DailySkip::whereBelongsTo($user)->whereBetween('date',[$month->toDateString(),$month->endOfMonth()->toDateString()])->distinct('date')->count('date');
        $daysWithNoSkips = max(0, now()->isSameMonth($month) ? now()->day - $daysTracked : $month->daysInMonth - $daysTracked);
        return view('calendar.show', compact('selectedDate','month','activities','skippedIds','dailySkips','monthSkips','calendarCounts','daysTracked','daysWithNoSkips'));
    }
    public function sync(SkipActivitiesRequest $request, string $date): RedirectResponse
    {
        $selectedDate = CarbonImmutable::parse($date)->toDateString(); $user = Auth::user();
        $activeIds = Activity::where('is_active', true)->pluck('id')->all();
        $ids = collect($request->validated('activities', []))->intersect($activeIds)->values();
        DailySkip::whereBelongsTo($user)->whereDate('date',$selectedDate)->whereNotIn('activity_id',$ids)->delete();
        $ids->each(fn ($id) => DailySkip::firstOrCreate(['user_id'=>$user->id,'activity_id'=>$id,'date'=>$selectedDate]));
        return back()->with('status','Skips updated for '.CarbonImmutable::parse($date)->format('F j, Y').'.');
    }
    public function destroy(string $date, Activity $activity): RedirectResponse
    {
        DailySkip::whereBelongsTo(Auth::user())->whereDate('date',CarbonImmutable::parse($date))->whereBelongsTo($activity)->delete();
        return back()->with('status', $activity->name.' unmarked.');
    }
}
