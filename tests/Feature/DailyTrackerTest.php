<?php
namespace Tests\Feature;
use App\Models\Activity;use App\Models\DailySkip;use App\Models\User;use Illuminate\Foundation\Testing\RefreshDatabase;use Tests\TestCase;
class DailyTrackerTest extends TestCase
{ use RefreshDatabase; public function test_username_login_creates_user(): void { $this->post('/login',['username'=>'Taylor'])->assertRedirect('/dashboard'); $this->assertAuthenticated(); $this->assertDatabaseHas('users',['username'=>'taylor']); }
public function test_skip_sync_does_not_duplicate_records(): void { $user=User::create(['username'=>'demo']); $activity=Activity::create(['name'=>'Breakfast','slug'=>'breakfast','is_active'=>true]); $this->actingAs($user)->post('/calendar/2026-08-31/skip',['activities'=>[$activity->id]])->assertRedirect(); $this->actingAs($user)->post('/calendar/2026-08-31/skip',['activities'=>[$activity->id]])->assertRedirect(); $this->assertSame(1, DailySkip::count()); }}
