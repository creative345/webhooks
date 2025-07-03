<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $connection = 'webhooks';
    
    protected $fillable = [
        'provider',
        'event_type',
        'event_id',
        'resource_id',
        'amount',
        'currency',
        'customer_email',
        'customer_id',
        'payment_method',
        'status',
        'raw_payload',
        'received_at',
        'processed_at',
        'is_test'
    ];

    protected $casts = [
        'raw_payload' => 'array',
        'received_at' => 'datetime',
        'processed_at' => 'datetime',
        'is_test' => 'boolean',
        'amount' => 'decimal:2'
    ];

    // Accessor for customer name (from email or customer_id)
    public function getCustomerNameAttribute()
    {
        if ($this->customer_email) {
            return $this->customer_email;
        }
        return $this->customer_id ? 'Customer ' . substr($this->customer_id, -8) : 'Unknown Customer';
    }

    // Accessor for gateway (maps provider to display name)
    public function getGatewayAttribute()
    {
        return ucfirst($this->provider);
    }

    // Accessor for date (formatted received_at)
    public function getDateAttribute()
    {
        return $this->received_at ? $this->received_at->format('M d, Y H:i') : 'N/A';
    }

    // Scope for filtering by provider
    public function scopeByProvider($query, $provider)
    {
        return $query->where('provider', strtolower($provider));
    }

    // Scope for filtering by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', strtolower($status));
    }

    // Scope for excluding test payments
    public function scopeExcludeTest($query)
    {
        return $query->where('is_test', false);
    }

    // Scope for only test payments
    public function scopeOnlyTest($query)
    {
        return $query->where('is_test', true);
    }
} 