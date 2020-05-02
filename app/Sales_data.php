<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Sales_data extends Model
{
    use Sortable;

    //to change the name of the table
    public $table = 'sales_data';

    protected $fillable = ['name'];

    public $sortable = ['id',
                        'order_id',
                        'po_number', 
                        'purchase_date', 
                        'cust_order', 
                        'cust_fname', 
                        'cust_city', 
                        'cust_country',
                        'cust_province',
                        'currency',
                        'tax',
                        'shipping',
                        'grand_total'];


}
