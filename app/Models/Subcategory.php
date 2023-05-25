<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $fillable = ['category_id', 'type','name','slug','language_id'];
    public $timestamps = false;

    public function childs()
    {
    	return $this->hasMany('App\Models\Childcategory')->where('status','=',1);
    }

    public function servicechilds()
    {
    	return $this->hasMany('App\Models\Childcategory')->where('status','=',1)->where('type',2);
    }

    public function category()
    {
    	return $this->belongsTo('App\Models\Category')->withDefault();
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function language()
    {
    	return $this->belongsTo('App\Models\Language','language_id')->withDefault();
    }  

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_replace(' ', '-', $value);
    }

    public function attributes() {
        return $this->morphMany('App\Models\Attribute', 'attributable');
    }

}
