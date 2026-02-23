<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use App\Traits\DateFormatTrait;

class School extends Model
{
    use SoftDeletes;
    use HasFactory;
    use DateFormatTrait;
    protected $fillable = [
        'name',
        'address',
        'support_phone',
        'support_email',
        'tagline',
        'logo',
        'image',
        'contact_person',
        'designation',
        'latitude',
        'longitude',
        'state',
        'city',
        'pin_code',
        'admin_id',
        'status',
        'domain',
        'database_name',
        'code',
        'type',
        'domain_type',
        'installed',
        'traccar_phone',
        'traccar_session_id',
        'traccar_session_expires_at'
    ];

    protected $casts = [
        'traccar_session_expires_at' => 'datetime'
    ];

    protected $hidden = ['database_name'];

    //Getter Attributes
    public function getLogoAttribute($value) {
        return url(Storage::url($value));
    }

    public function user(){
        return $this->belongsTo(User::class,'admin_id')->withTrashed();
    }

    public function subscription()
    {
        return $this->hasMany(Subscription::class);
    }

    public function addon()
    {
        $today_date = Carbon::now()->format('Y-m-d');
        return $this->hasManyThrough(Feature::class,AddonSubscription::class,'school_id','id','id','feature_id')
        ->where('start_date','<=',$today_date)->where('end_date','>=',$today_date);
    }

    public function features()
    {
        $today_date = Carbon::now()->format('Y-m-d');
        return $this->hasManyThrough(SubscriptionFeature::class,Subscription::class)->where('start_date','<=',$today_date)->where('end_date','>=',$today_date);
    }

    public function test() {
        return $this->features->merge($this->addon);
    }

    public function extra_school_details()
    {
        return $this->hasMany(ExtraSchoolData::class, 'school_id', 'id'); 
    }


//    public function features() {
////        return $this->subscription()->union($this->addon());
//        return ["subscriptions" => $this->subscription(), "addon" => $this->addon()];
//    }

    public function getCreatedAtAttribute()
    {
        return $this->formatDateValue($this->getRawOriginal('created_at'));
    }
    
    public function getUpdatedAtAttribute()
    {
        return $this->formatDateValue($this->getRawOriginal('updated_at'));
    }

    /**
     * Get Traccar session for this school
     */
    public function getTraccarSession()
    {
        // Check if session exists and is valid
        if ($this->traccar_session_id && 
            $this->traccar_session_expires_at && 
            $this->traccar_session_expires_at->isFuture()) {
            return $this->traccar_session_id;
        }

        // Generate new session
        return $this->refreshTraccarSession();
    }

    /**
     * Refresh Traccar session
     */
    public function refreshTraccarSession()
    {
        if (!$this->traccar_phone) {
            return null;
        }

        try {
            $response = \Illuminate\Support\Facades\Http::asForm()->post(
                "https://app.trackroutepro.com/Auth/verifyUser",
                ['phone' => $this->traccar_phone]
            );

            if ($response->successful()) {
                $sessionId = $response->json()['jsessionid'] ?? null;
                
                if ($sessionId) {
                    $this->update([
                        'traccar_session_id' => $sessionId,
                        'traccar_session_expires_at' => now()->addHours(2)
                    ]);
                    
                    return $sessionId;
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Traccar session refresh failed for school {$this->id}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Get vehicles relationship
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
