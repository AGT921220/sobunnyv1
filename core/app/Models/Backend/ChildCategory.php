<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCategory extends Model
{
    use HasFactory;

    protected $table = 'child_categories';
    protected $fillable = ['name','slug','category_id','sub_category_id','status','image', 'description'];

    public function category(){
        return $this->belongsTo('App\Models\Backend\Category');
    }

    public function subcategory(){
        return $this->belongsTo( SubCategory::class, 'sub_category_id', 'id');
    }

    public function metaData(){
        return $this->morphOne(MetaData::class,'meta_taggable');
    }
}
