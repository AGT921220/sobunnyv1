<?php

namespace App\Http\Controllers\Frontend\User;

use App\Feature\Age\UseCases\GetAllAges;
use App\Feature\BodyType\UseCases\GetAllBodyTypes;
use App\Feature\Breast\UseCases\GetAllBreasts;
use App\Feature\Cater\UseCases\GetAllCaters;
use App\Feature\Ethnicity\UseCases\GetAllEthnicitys;
use App\Feature\EyeColor\UseCases\GetAllEyeColors;
use App\Feature\Geneder\UseCases\GetAllGenders;
use App\Feature\HairColor\UseCases\GetAllHairColors;
use App\Feature\Height\UseCases\GetAllHeights;
use App\Feature\ServiceType\UseCases\GetAllServiceTypes;
use App\Feature\Servicing\UseCases\GetAllServicings;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\Backend\AdminNotification;
use App\Models\Backend\Category;
use App\Models\Backend\ChildCategory;
use App\Models\Backend\IdentityVerification;
use App\Models\Backend\Listing;
use App\Models\Backend\ListingTag;
use App\Models\Backend\Page;
use App\Models\Backend\SubCategory;
use App\Models\Common\ListingReport;
use App\Models\Frontend\ListingFavorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Blog\app\Models\Tag;
use Modules\Brand\app\Models\Brand;
use Modules\CountryManage\app\Models\City;
use Modules\CountryManage\app\Models\Country;
use Modules\CountryManage\app\Models\State;
use Modules\Membership\app\Models\UserMembership;

class ListingController extends Controller
{

    private $getAllGenders;
    private $getAllEthnicitys;
    private $getAllAges;
    private $getAllBreasts;
    private $getAllCaters;
    private $getAllBodyTypes;
    private $getAllEyeColors;
    private $getAllHairColors;
    private $getAllHeighs;
    private $getAllServiceTypes;
    private $getAllServicings;
    public function __construct(
        GetAllGenders $getAllGenders,
        GetAllEthnicitys $getAllEthnicitys,
        GetAllAges $getAllAges,
        GetAllCaters $getAllCaters,
        GetAllBreasts $getAllBreasts,
        GetAllEyeColors $getAllEyeColors,
        GetAllHairColors $getAllHairColors,
        GetAllHeights $getAllHeighs,
        GetAllServiceTypes $getAllServiceTypes,
        GetAllServicings $getAllServicings,
        GetAllBodyTypes $getAllBodyTypes

    ) {
        $this->getAllGenders = $getAllGenders;
        $this->getAllEthnicitys = $getAllEthnicitys;
        $this->getAllAges = $getAllAges;
        $this->getAllBreasts = $getAllBreasts;
        $this->getAllCaters = $getAllCaters;
        $this->getAllBodyTypes = $getAllBodyTypes;
        $this->getAllEyeColors = $getAllEyeColors;
        $this->getAllHairColors = $getAllHairColors;
        $this->getAllHeighs = $getAllHeighs;
        $this->getAllServiceTypes = $getAllServiceTypes;
        $this->getAllServicings = $getAllServicings;
    }

    public function allListing(Request $request)
    {
        if(auth()->user()->role==User::CLIENT_ROLE)
        {
            return redirect()->route('user.dashboard');
        }
        $listings = Listing::where('user_id', Auth::guard('web')->user()->id)->latest()->paginate(5);
        return view('frontend.user.listings.all-listings', compact('listings'));
    }

    // add listing page
    public function addListing(Request $request)
    {

        if(auth()->user()->role==User::CLIENT_ROLE)
        {
            return redirect()->route('user.dashboard');
        }
        // Check Membership Status
        if (moduleExists('Membership') && membershipModuleExistsAndEnable('Membership')) {
            $user_membership_check = UserMembership::where('user_id', Auth::guard('web')->user()->id)->first();
            if ($user_membership_check && $user_membership_check->status === 0 || $user_membership_check->payment_status == 'pending') {
                toastr_error(__('Your membership plan is inactive. Please activate your plan before creating listings.'));
                return redirect()->back();
            }
        }

        if ($request->isMethod('post')) {
            //user Verify check
            if (get_static_option('listing_create_settings') == 'verified_user') {
                $user_identity = IdentityVerification::select('user_id', 'status')->where('user_id', Auth::guard('web')->user()->id)->first();
                $user_verified_status = $user_identity?->status ?? 0;
                if ($user_verified_status != 1) {
                    toastr_error(__('You are not verified. to add listings you must have to verify your account first'));
                    return redirect()->back();
                }
            }

            //check membership
            if (moduleExists('Membership')) {
                if (membershipModuleExistsAndEnable('Membership')) {
                    $user_membership = UserMembership::where('user_id', Auth::guard('web')->user()->id)->first();
                    // if user membership is null
                    if (is_null($user_membership)) {
                        toastr_error(__('you have to membership a package to create listings'));
                        return redirect()->back();
                    }

                    $user_total_listing_count = Listing::where('user_id', Auth::guard('web')->user()->id)->count();


                    // check user membership all listing limit
                    if ($user_membership->listing_limit == 0 && $user_membership->expire_date <= Carbon::now()) {
                        session()->flash('message', __('Your Membership is expired'));
                        return redirect()->back();
                    } elseif ($user_membership->listing_limit === 0) {
                        toastr_error(__('Your membership listing limit is over!. please renew it'));
                        return redirect()->back();
                    } elseif ($user_membership->expire_date <= Carbon::now()) {
                        toastr_error(__('Your Membership is expired'));
                        return redirect()->back();
                    }

                    // Check if the user has exceeded the allowed number of gallery images
                    $initial_gallery_images = $user_membership->initial_gallery_images;
                    $gallery_images = $request->gallery_images;
                    $gallery_images_input = explode('|', $gallery_images);
                    $gallery_images_input_count = count($gallery_images_input);

                    if ($gallery_images_input_count > $initial_gallery_images) {
                        toastr_error(__('You have exceeded the maximum number of gallery images allowed by your membership package.'));
                        return redirect()->back();
                    }

                    // Check featured listing
                    if (!empty($request->is_featured)) {
                        if ($user_membership->initial_featured_listing != 0) {
                            if ($user_membership->featured_listing === 0) {
                                toastr_error(__('You have exceeded the maximum number of featured listings allowed by your membership package.'));
                                return redirect()->back();
                            }
                        }
                    }
                }
            }

            // Validation start
            $request->validate([
                // 'category_id' => 'required',


                'gender_id' => 'required',
                'ethnicity_id' => 'required',
                'age_id' => 'required',
                'breasts_id' => 'required',
                // 'cater_id' => 'required',
                'body_type_id' => 'required',
                'eye_color_id' => 'required',
                'hair_color_id' => 'required',
                'service_type_id' => 'required',
                'servicing_id' => 'required',
                'heights_id' => 'required',

                'title' => 'required|max:191',
                // 'description' => 'required|min:150',
                'slug' => 'required|max:255|unique:listings',
                // 'price' => 'required|numeric'
            ], [
                'title.required' => __('The title field is required.'),
                'title.max' => __('The title must not exceed 191 characters.'),
                // 'description.required' => __('The description field is required.'),
                // 'description.min' => __('The description must be at least 150 characters.'),
                'slug.required' => __('The slug field is required.'),
                'slug.unique' => __('The slug has already been taken.'),
                // 'price.required' => __('The price field is required3.'),
                // 'price.numeric' => __('The price must be a numeric value.')
            ]);

            $user = User::where('id', Auth::guard('web')->user()->id)->first();
            $slug = !empty($request->slug) ? $request->slug : $request->title;

            if (get_static_option('listing_create_status_settings') == 'approved') {
                $status = 1;
            } else {
                $status = 0;
            }

            // video url
            $video_url = null;
            if (!empty($request->video_url)) {
                $video_url = getYoutubeEmbedUrl($request->video_url);
            }

            // listing phone number
            $listing_phone = $request->country_code ?? $request->phone;

            // Create a new listing
            $listing = new Listing();
            $listing->user_id = $user->id;
            $listing->category_id = $request->category_id;
            $listing->sub_category_id = $request->sub_category_id;
            $listing->child_category_id = $request->child_category_id;
            $listing->country_id = $request->country_id;
            $listing->state_id = $request->state_id;
            $listing->city_id = $request->city_id;
            $listing->brand_id = $request->brand_id;
            $listing->title = $request->title;
            $listing->slug = Str::slug(purify_html($slug), '-', null);
            $listing->description = $request->description;
            $listing->price = $request->price;
            $listing->negotiable = $request->negotiable ?? 0;
            $listing->condition = $request->condition;
            $listing->authenticity = $request->authenticity;
            $listing->phone = $listing_phone;
            $listing->phone_hidden = $request->phone_hidden ?? 0;
            $listing->image = $request->image;
            $listing->gallery_images = $request->gallery_images;
            $listing->video_url = $video_url;
            $listing->address = $request->address;
            $listing->lat = $request->latitude;
            $listing->lon = $request->longitude;
            $listing->is_featured =  $request->is_featured ?? 0;
            $listing->status = $status;

            $listing->gender_id = $request->input('gender_id');
            $listing->ethnicity_id = $request->input('ethnicity_id');
            $listing->age_id = $request->input('age_id');
            $listing->breast_id = $request->input('breasts_id');
            $listing->cater_id = $request->input('cater_id');
            $listing->body_type_id = $request->input('body_type_id');
            $listing->eyecolor_id = $request->input('eye_color_id');
            $listing->hair_color_id = $request->input('hair_color_id');
            $listing->service_type_id = $request->input('service_type_id');
            $listing->servicing_id = $request->input('servicing_id');
            $listing->height_id = $request->input('heights_id');
            $listing->city_id = $request->input('city_id');
            $listing->cater_1 = !!$request->input('cater_1');
            $listing->cater_2 = !!$request->input('cater_2');
            $listing->cater_3 = !!$request->input('cater_3');
            $listing->chat = !!$request->input('chat');


            if ($request->input('more_cities_id')) {
                $listing->second_city_id = $request->input('more_cities_id')[0];
                $listing->third_city_id = $request->input('more_cities_id')[1]??null;
            }
            $tags_name = '';
            if (!empty($request->tags)) {
                $tags_name = Tag::whereIn('id', $request->tags)->pluck('name')->implode(', ');
            }
            $Metas = [
                'meta_title' => purify_html($request->title),
                'meta_tags' => purify_html($tags_name),
                'meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'facebook_meta_tags' => purify_html($tags_name),
                'facebook_meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'facebook_meta_image' => $request->image,
                'twitter_meta_tags' => purify_html($tags_name),
                'twitter_meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'twitter_meta_image' => $request->image,
            ];
            $listing->save();
            // Retrieve the last inserted ID
            $last_listing_id = $listing->id;

            // create tags
            if ($request->filled('tags')) {
                foreach ($request->tags as $tagId) {
                    ListingTag::create([
                        'listing_id' => $last_listing_id,
                        'tag_id' => $tagId,
                    ]);
                }
            }

            $user_id = Auth::guard('web')->user()->id;

            // if membership system decrement listing limit
            if (moduleExists('Membership')) {
                if (membershipModuleExistsAndEnable('Membership')) {
                    // listing limit
                    UserMembership::where('user_id', $user_id)->update([
                        'listing_limit' => DB::raw(sprintf("listing_limit - %s", (int)strip_tags(1))),
                    ]);

                    // is featured listing
                    $user_membership_check = UserMembership::where('user_id', $user_id)->first();
                    if ($user_membership_check->initial_featured_listing != 0) {
                        if (!empty($request->is_featured)) {
                            UserMembership::where('user_id', $user_id)->update([
                                'featured_listing' => DB::raw(sprintf("featured_listing - %s", (int)strip_tags(1))),
                            ]);
                        }
                    }
                }
            }

            //create listing notification to admin
            AdminNotification::create([
                'identity' => $last_listing_id,
                'user_id' => $user_id,
                'type' => 'Create Listing',
                'message' => __('A new project has been created'),
            ]);

            // sent email to admin
            if (get_static_option('listing_create_status_settings') == 'pending') {
                try {
                    $subject = get_static_option('listing_approve_subject') ?? __('New Listing Approve Request');
                    $message = get_static_option('listing_approve_message');
                    $message = str_replace(["@listing_id"], [$last_listing_id], $message);
                    Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                        'subject' => $subject,
                        'message' => $message
                    ]));
                } catch (\Exception $e) {
                    //
                }
            }

            return redirect()->route('user.all.listing')->with(toastr_success(__('Listing Added Success')));
        }


        //check membership
        if (moduleExists('Membership')) {
            if (membershipModuleExistsAndEnable('Membership')) {
                $user_membership = UserMembership::where('user_id', Auth::guard('web')->user()->id)->first();
                if (is_null($user_membership)) {
                    toastr_error(__('you have to membership a package to create listings'));
                    return redirect()->back();
                }
            }
        }

        $categories = Category::where('status', 1)->get();
        $sub_categories = SubCategory::where('status', 1);
        $all_countries = Country::all_countries();
        $all_states = State::all_states();
        $all_cities = City::all_cities();
        $tags = Tag::where('status', 'publish')->get();
        $user = Auth::guard('web')->user();
        $brands = Brand::where('status', 1)->get();
        $user_identity_verifications = IdentityVerification::where('user_id', $user->id)->first();

        // if membership module exits
        $membership_page_url = get_static_option('membership_plan_page') ? Page::select('slug')->find(get_static_option('membership_plan_page'))->slug : '';
        $user_featured_listing_enable = false;
        $user_listing_limit_check = false;
        if (moduleExists('Membership')) {
            if (membershipModuleExistsAndEnable('Membership')) {
                $user_membership = UserMembership::where('user_id', $user->id)->first();
                if ($user_membership->featured_listing != 0) {
                    $user_featured_listing_enable = true;
                }
                if ($user_membership->listing_limit === 0) {
                    $user_listing_limit_check = true;
                }
            }
        }
        $genders = $this->getAllGenders->__invoke();
        $ethnicities = $this->getAllEthnicitys->__invoke();
        $ages = $this->getAllAges->__invoke();
        $breasts = $this->getAllBreasts->__invoke();
        $caters = $this->getAllCaters->__invoke();
        $bodyTypes = $this->getAllBodyTypes->__invoke();
        $eyeColors = $this->getAllEyeColors->__invoke();
        $hairColors = $this->getAllHairColors->__invoke();
        $serviceTypes = $this->getAllServiceTypes->__invoke();
        $servicings = $this->getAllServicings->__invoke();
        $heights = $this->getAllHeighs->__invoke();

        return view('frontend.user.listings.add-listing', compact(
            'membership_page_url',
            'user_featured_listing_enable',
            'user_listing_limit_check',
            'user',
            'brands',
            'categories',
            'sub_categories',
            'all_countries',
            'all_states',
            'all_cities',
            'tags',
            'user_identity_verifications',
            'genders',
            'ethnicities',
            'ages',
            'breasts',
            'caters',
            'bodyTypes',
            'eyeColors',
            'hairColors',
            'serviceTypes',
            'servicings',
            'heights'

        ));
    }

    // Edit listing page
    public function editListing(Request $request, $id)
    {
        if ($request->isMethod('post')) {

            // Validation start
            $request->validate([
                // 'category_id' => 'required',
                'title' => 'required|max:191',
                // 'description' => 'required|min:150',
                'slug' => 'required|unique:listings,slug,' . $id . ',id',
                'gender_id' => 'required',
                'ethnicity_id' => 'required',
                'age_id' => 'required',
                'breasts_id' => 'required',
                // 'cater_id' => 'required',
                'body_type_id' => 'required',
                'eye_color_id' => 'required',
                'hair_color_id' => 'required',
                'service_type_id' => 'required',
                'servicing_id' => 'required',
                'heights_id' => 'required',

                // 'price' => 'required|numeric'
            ], [
                'title.required' => __('The title field is required.'),
                'title.max' => __('The title must not exceed 191 characters.'),
                // 'description.required' => __('The description field is required.'),
                // 'description.min' => __('The description must be at least 150 characters.'),
                'slug.required' => __('The slug field is required.'),
                'slug.unique' => __('The slug has already been taken.'),
                // 'price.required' => __('The price field is required.'),
                // 'price.numeric' => __('The price must be a numeric value.')
            ]);

            // country, state, city
            $user = User::where('id', Auth::guard('web')->user()->id)->first();
            $slug = !empty($request->slug) ? $request->slug : $request->title;

            if (get_static_option('listing_create_status_settings') == 'approved') {
                $status = 1;
            } else {
                $status = 0;
            }

            // video url
            $video_url = null;
            if (!empty($request->video_url)) {
                $video_url = getYoutubeEmbedUrl($request->video_url);
            }

            // listing phone number
            $listing_phone = $request->country_code ?? $request->phone;

            // Edit listing
            $listing = Listing::findOrFail($id);
            $listing->user_id = $user->id;
            $listing->category_id = $request->category_id;
            $listing->sub_category_id = $request->sub_category_id;
            $listing->child_category_id = $request->child_category_id;
            $listing->country_id = $request->country_id;
            $listing->state_id = $request->state_id;
            $listing->city_id = $request->city_id;
            $listing->brand_id = $request->brand_id;
            $listing->title = $request->title;
            $listing->slug = Str::slug(purify_html($slug), '-', null);
            $listing->description = $request->description;
            $listing->price = $request->price;
            $listing->negotiable = $request->negotiable ?? 0;
            $listing->condition = $request->condition;
            $listing->authenticity = $request->authenticity;
            $listing->phone = $listing_phone;
            $listing->phone_hidden = $request->phone_hidden ?? 0;
            $listing->image = $request->image;
            $listing->gallery_images = $request->gallery_images;
            $listing->video_url = $video_url;
            $listing->address = $request->address;
            $listing->lat = $request->latitude;
            $listing->lon = $request->longitude;
            $listing->is_featured = $request->is_featured ?? 0;
            $listing->status = $status;
            $listing->ethnicity_id = $request->input('ethnicity_id');
            $listing->age_id = $request->input('age_id');
            $listing->breast_id = $request->input('breasts_id');
            $listing->cater_id = $request->input('cater_id');
            $listing->body_type_id = $request->input('body_type_id');
            $listing->eyecolor_id = $request->input('eye_color_id');
            $listing->hair_color_id = $request->input('hair_color_id');
            $listing->service_type_id = $request->input('service_type_id');
            $listing->servicing_id = $request->input('servicing_id');
            $listing->height_id = $request->input('heights_id');
            $listing->city_id = $request->input('city_id');
            $listing->cater_1 = !!$request->input('cater_1');
            $listing->cater_2 = !!$request->input('cater_2');
            $listing->cater_3 = !!$request->input('cater_3');
            $listing->chat = !!$request->input('chat');

            if ($request->input('more_cities_id')) {
                $listing->second_city_id = $request->input('more_cities_id')[0];
                $listing->third_city_id = $request->input('more_cities_id')[1]??null;
            }


            $tags_name = '';
            if (!empty($request->tags)) {
                $tags_name = Tag::whereIn('id', $request->tags)->pluck('name')->implode(', ');
            }
            $Metas = [
                'meta_title' => purify_html($request->title),
                'meta_tags' => purify_html($tags_name),
                'meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'facebook_meta_tags' => purify_html($tags_name),
                'facebook_meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'facebook_meta_image' => $request->image,
                'twitter_meta_tags' => purify_html($tags_name),
                'twitter_meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'twitter_meta_image' => $request->image,
            ];
            $listing->save();
            // Retrieve the last inserted ID
            $last_listing_id = $listing->id;

            // Edit tags
            if ($request->filled('tags')) {
                $listing->tags()->detach();
                foreach ($request->tags as $tagId) {
                    ListingTag::create([
                        'listing_id' => $last_listing_id,
                        'tag_id' => $tagId,
                    ]);
                }
            }

            // send email to admin
            try {
                $message = get_static_option('service_approve_message');
                $message = str_replace(["@service_id"], [$last_listing_id], $message);
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => get_static_option('service_approve_subject') ?? __('New Listing Approve Request'),
                    'message' => $message
                ]));
            } catch (\Exception $e) {
                //
            }

            return redirect()->route('user.all.listing')->with(toastr_success(__('Listing Updated Success')));
            return back()->with(toastr_success(__('Listing Updated Success')));
        }

        $listing = Listing::findOrFail($id);
        $categories = Category::where('status', 1)->get();
        $sub_categories = SubCategory::where('status', 1)->get();
        $child_categories = ChildCategory::where('status', 1)->get();
        $all_countries = Country::all_countries();
        $all_states = State::all_states();
        $all_cities = City::all_cities();
        $brands = Brand::where('status', 1)->get();
        $tags = Tag::where('status', 'publish')->get();

        // if membership module exits
        $membership_page_url = get_static_option('membership_plan_page') ? Page::select('slug')->find(get_static_option('membership_plan_page'))->slug : '';
        $user_featured_listing_enable = false;
        $user_listing_limit_check = false;
        if (moduleExists('Membership')) {
            if (membershipModuleExistsAndEnable('Membership')) {
                $user_membership = UserMembership::where('user_id', Auth::guard('web')->user()->id)->first();
                if (!empty($user_membership)) {
                    if ($user_membership->featured_listing != 0) {
                        $user_featured_listing_enable = true;
                    }
                    if ($user_membership->listing_limit === 0) {
                        $user_listing_limit_check = true;
                    }
                }
            }
        }

        $genders = $this->getAllGenders->__invoke();
        $ethnicities = $this->getAllEthnicitys->__invoke();
        $ages = $this->getAllAges->__invoke();
        $breasts = $this->getAllBreasts->__invoke();
        $caters = $this->getAllCaters->__invoke();
        $bodyTypes = $this->getAllBodyTypes->__invoke();
        $eyeColors = $this->getAllEyeColors->__invoke();
        $hairColors = $this->getAllHairColors->__invoke();
        $serviceTypes = $this->getAllServiceTypes->__invoke();
        $servicings = $this->getAllServicings->__invoke();
        $heights = $this->getAllHeighs->__invoke();

        return view('frontend.user.listings.edit-listing', compact(
            'membership_page_url',
            'user_featured_listing_enable',
            'user_listing_limit_check',
            'listing',
            'brands',
            'categories',
            'sub_categories',
            'child_categories',
            'all_countries',
            'all_states',
            'all_cities',
            'tags',
            'genders',
            'ethnicities',
            'ages',
            'breasts',
            'caters',
            'bodyTypes',
            'eyeColors',
            'hairColors',
            'serviceTypes',
            'servicings',
            'heights'

        ));
    }

    public function deleteListing($id = null)
    {
        if (Listing::find($id)) {
            ListingTag::where('listing_id', $id)->delete();
            ListingFavorite::where('listing_id', $id)->delete();
            ListingReport::where('listing_id', $id)->delete();

            // Delete the main Listing record
            Listing::find($id)->delete();

            toastr_error(__('Listing Delete Success---'));
            return redirect()->back();
        } else {
            toastr_error(__('Listing not found'));
            return redirect()->back();
        }
    }

    public function listingPublishedStatus($id)
    {
        // First check if the listing exists
        $listing = Listing::find($id);
        if (!$listing) {
            $message = __('Listing not found.');
            toastr()->error($message);
            return redirect()->back();
        }

        // Check listing approval status
        if ($listing->status === 0) {
            $message = __('This listing is not yet approved. It will be published after approval.');
            toastr()->warning($message);
            return redirect()->back();
        }

        // Toggle listing publication status
        $listing->is_published = !$listing->is_published;
        $listing->save();

        // Show appropriate message
        if ($listing->is_published) {
            // Listing is published
            $message = __('Listing has been successfully published.');
            toastr()->success($message);
        } else {
            // Listing is unpublished
            $message = __('Listing has been successfully unpublished.');
            toastr()->warning($message);
        }

        return redirect()->back();
    }
}
