<?php
/**
 * Created by PhpStorm.
 * User: Hiram
 * Date: 19/12/2016
 * Time: 09:53 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ComprobanteDetalle extends Model
{
    protected $table = 'comprobante_detalle';

    public function producto()
    {
        return $this->belongsTo('App\Models\Producto');
    }
}