<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'contract_id',
        'vehicle_id',
        'invoice_date',
        'rental_days',
        'vehicle_amount',
        'service_fee',
        'total_amount',
        'status'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
    public function invoiceDetails() {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
