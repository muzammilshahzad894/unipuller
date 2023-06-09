<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $fillable = ['name','slug','photo','image','language_id'];
    public $timestamps = false;

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
