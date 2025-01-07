<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;

    protected $fillable = [
        'title', 'title_h1', 'slug', 'product_categories_id', 'discount', 'type', 'description', 'body', 'price', 'price_usd', 'price_euro', 'price_old', 'unit', 'images', 'tags', 'priority', 'status', 'place_of_delivery', 'updated_at', 'tag_title',
        'seo_title', 'seo_description', 'seo_follow', 'seo_index', 'seo_canonical', 'schema', 'factory_id', 'standard_id', 'size_id'
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

    public function factory()
    {
        return $this->hasOne(Factories::class, 'id', 'factory_id');
    }

    public function size()
    {
        return $this->hasOne(Sizes::class, 'id', 'size_id');
    }

    public function standard()
    {
        return $this->hasOne(Standards::class, 'id', 'standard_id');
    }

    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    public function scopeOldPrice()
    {
        return $this->price_old;
    }

    public function scopeFluctuationPrice()
    {
        if ($this->price_old != 0)
            return round((($this->price - $this->price_old) / $this->price_old) * 100, 2);
        else
            return 0;
    }


}
