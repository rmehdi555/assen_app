<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Standards extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title','body','slug','images','priority','status','product_categories_id','tag_title',
        'seo_title','seo_description','seo_follow','seo_index','seo_canonical','schema'
    ];

    /**

     * The attributes that should be mutated to dates.

     *

     * @var array

     */
    protected $dates = ['deleted_at'];


    public function category()
    {
        return $this->hasOne(ProductCategories::class, 'id', 'product_categories_id');
    }
}
