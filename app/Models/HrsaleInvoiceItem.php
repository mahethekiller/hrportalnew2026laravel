<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrsaleInvoiceItem extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrsale_invoices_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_id',
        'project_id',
        'item_name',
        'item_tax_type',
        'item_tax_rate',
        'item_qty',
        'item_unit_price',
        'item_sub_total',
        'sub_total_amount',
        'total_tax',
        'discount_type',
        'discount_figure',
        'total_discount',
        'grand_total'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
