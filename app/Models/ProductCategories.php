<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategories extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;
    protected $fillable = [
        'title', 'slug', 'description','body','parent_id','images','tags','icon','priority','status',
        'seo_title','seo_description','seo_follow','seo_index','seo_canonical','is_show'
    ];
    /**

     * The attributes that should be mutated to dates.

     *

     * @var array

     */
    protected $dates = ['deleted_at'];



    public function parent()
    {
        return $this->belongsTo('App\ProductCategories','parent_id')->where('parent_id',0);
    }

    public function children()
    {
        return $this->hasMany('App\ProductCategories','parent_id');
    }

    public function products()
    {
        return $this->hasMany('App\Products'); // This only gets the products of the CURRENT category
    }
    public function activeProducts($limit='10')
    {
        return $this->hasMany('App\Products')->where('status','=','1')->orderBy('priority','desc')->limit($limit)->get(); // This only gets the products of the CURRENT category
    }
}
