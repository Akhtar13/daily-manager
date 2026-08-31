<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Activity extends Model
{
    protected $fillable = ['name','slug','is_active'];
    protected $casts = ['is_active'=>'boolean'];
    public function dailySkips(): HasMany { return $this->hasMany(DailySkip::class); }
}
