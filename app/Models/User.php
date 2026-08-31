<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable
{
    use HasFactory;
    protected $fillable = ['username'];
    protected $hidden = ['remember_token'];
    public function dailySkips(): HasMany { return $this->hasMany(DailySkip::class); }
}
