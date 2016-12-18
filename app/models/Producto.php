<?php
/**
 * Created by PhpStorm.
 * User: Hiram
 * Date: 15/12/2016
 * Time: 02:08 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';

    public function getMargenAttribute() : float
    {
        $ingreso = $this->precio - $this->costo;
        return round($ingreso / $this->costo * 100, 0);
    }

    public function getTieneFotoAttribute() : string
    {
        return (empty($this->foto) ? 'No' : 'Si');
    }

}