<?php

namespace App\Models\Backend;

use App\Models\Age;
use App\Models\BodyType;
use App\Models\Breast;
use App\Models\Cater;
use App\Models\Common\ListingReport;
use App\Models\Ethnicity;
use App\Models\EyeColor;
use App\Models\Frontend\GuestListing;
use App\Models\Frontend\ListingFavorite;
use App\Models\Gender;
use App\Models\HairColor;
use App\Models\Height;
use App\Models\ServiceType;
use App\Models\Servicing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Blog\app\Models\Tag;
use Modules\Brand\app\Models\Brand;
use Modules\CountryManage\app\Models\City;
use Modules\CountryManage\app\Models\Country;
use Modules\CountryManage\app\Models\State;

class Listing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'listings';
    protected $fillable = [
        'user_id',
        'admin_id',
        'category_id',
        'sub_category_id',
        'child_category_id',
        'brand_id',
        'country_id',
        'state_id',
        'city_id',
        'title',
        'slug',
        'description',
        'image',
        'gallery_images',
        'video_url',
        'price',
        'negotiable',
        'condition',
        'contact_name',
        'email',
        'phone',
        'phone_hidden',
        'address',
        'lon',
        'lat',
        'is_featured',
        'view',
        'status',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function listing_creator()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function category(){
        return $this->belongsTo('App\Models\Backend\Category');
    }

    public function subcategory()
    {
        return $this->belongsTo(\App\Models\Backend\SubCategory::class, 'sub_category_id', 'id');
    }

    public function childcategory(){
        return $this->belongsTo(ChildCategory::class, 'child_category_id', 'id');
    }

    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function secondaryCity()
    {
        return $this->belongsTo(City::class, 'second_city_id');
    }
    public function tertiaryCity()
    {
        return $this->belongsTo(City::class, 'third_city_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function admin(){
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function metaData(){
        return $this->morphOne(MetaData::class,'meta_taggable');
    }
    public function listingCity(){
        return $this->belongsTo(City::class,'city_id','id');
    }

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'listing_tags', 'listing_id', 'tag_id');
    }

    public function scopeAdminListings($query)
    {
        return $query->whereNotNull('admin_id');
    }

    public function scopeUserListings($query)
    {
        return $query->whereNotNull('user_id')->where('user_id', '!=', 0)->where('admin_id', null);
    }

    public function scopeGuestListings($query)
    {
        return $query->where('user_id', 0);
    }

    public function listingReports()
    {
        return $this->hasMany(ListingReport::class, 'listing_id');
    }

    public function listingTags()
    {
        return $this->hasMany(ListingTag::class, 'listing_id');
    }

    public function listingFavorites()
    {
        return $this->hasMany(ListingFavorite::class, 'listing_id');
    }

    public function user_membership(){
        return $this->belongsTo('\Modules\Membership\app\Models\UserMembership','user_id','user_id')
            ->where('expire_date', '>=', now())
            ->where('user_id', '!=', 0); // check guest user listing
    }
    public function guestListing(){
        return $this->hasOne(GuestListing::class, 'listing_id', 'id');
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }
    
    public function ethnicity()
    {
        return $this->belongsTo(Ethnicity::class);
    }
    
    public function age()
    {
        return $this->belongsTo(Age::class);
    }
    
    public function breasts()
    {
        return $this->belongsTo(Breast::class);
    }
    
    public function cater()
    {
        return $this->belongsTo(Cater::class);
    }
    
    public function bodyType()
    {
        return $this->belongsTo(BodyType::class);
    }
    
    public function eyeColor()
    {
        return $this->belongsTo(EyeColor::class);
    }
    
    public function hairColor()
    {
        return $this->belongsTo(HairColor::class);
    }
    
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }
    
    public function servicing()
    {
        return $this->belongsTo(Servicing::class);
    }
    
    public function height()
    {
        return $this->belongsTo(Height::class);
    }
    
}
