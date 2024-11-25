<?php

use App\Http\Controllers\Backend\AdvertisementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend\AdminDashboardController;
use App\Http\Controllers\Backend\AdminProfileController;
use App\Http\Controllers\Backend\MediaUploadController;
use App\Http\Controllers\Backend\AdminNotificationController;
use App\Http\Controllers\Backend\GeneralSettingsController;
use App\Http\Controllers\Backend\CustomFontController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\EmailSettingsController;
use App\Http\Controllers\Backend\EmailTemplateController;
use App\Http\Controllers\Backend\PaymentGatewaySettingsController;
use App\Http\Controllers\Backend\NoticeController;
use App\Http\Controllers\Backend\MaintainsPageController;
use App\Http\Controllers\Backend\Manage404PageController;
use App\Http\Controllers\Backend\WidgetsController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\ChildCategoryController;
use App\Http\Controllers\Backend\PageBuilderController;
use App\Http\Controllers\Backend\PagesController;
use App\Http\Controllers\Backend\PageSettingsController;
use App\Http\Controllers\Backend\MapSettings;

Route::middleware(['setlang'])->group(function () {
        // Dashboard
        Route::prefix('dashboard')->group(function () {
            Route::get('/', [AdminDashboardController::class, 'adminDashboard'])->name('admin.dashboard')->permission('admin-dashboard');
            Route::get('/getUserData', [AdminDashboardController::class, 'getUserData'])->name('admin.get.user.graph.data');
            Route::get('/getListingData', [AdminDashboardController::class, 'getListingData'])->name('admin.get.listing.graph.data');
        });

        // General Settings
        Route::get('/dark-mode-toggle', 'AdminDashboardController@dark_mode_toggle')->name('admin.dark.mode.toggle');
        Route::get('/settings', [AdminDashboardController::class, 'adminSettings'])->name('admin.profile.settings');
        Route::get('/dark-mode-toggle',  [AdminDashboardController::class, 'darkModeToggle'])->name('admin.dark.mode.toggle');

        // admin profile settings
        Route::get('/logout', [AdminProfileController::class, 'adminLogout'])->name('admin.logout');
        Route::get('/profile-update', [AdminProfileController::class, 'adminProfile'])->name('admin.profile.update');
        Route::post('/profile-update', [AdminProfileController::class, 'adminProfileUpdate']);
        Route::get('/password-change',[AdminProfileController::class, 'adminPassword'])->name('admin.profile.password.change');
        Route::post('/password-change',[AdminProfileController::class, 'adminPasswordChange']);


    //account suspend active
    Route::group(['prefix' => 'account'],function(){
        Route::controller(\App\Http\Controllers\Backend\SuspendActiveController::class)->group(function () {
            Route::match(['get','post'],'suspend/{id}','suspend')->name('admin.account.suspend');
            Route::post('unsuspend/{id}','unsuspend')->name('admin.account.unsuspend');
        });
    });

    // Listing manage
    Route::group(['prefix' => 'listings'],function(){
        // all user listings
        Route::controller(\App\Http\Controllers\Backend\UserListingManageController::class)->group(function () {
            Route::get('/user-all-listings','all_listings')->name('admin.user.all.listings')->permission('user-listing-list');
            Route::get('/details/{id}','listingDetails')->name('admin.listings.details');
            Route::post('/user-all/approved','userListingsAllApproved')->name('admin.listings.user.all.approved')->permission('user-listing-approved');
            Route::post('/published/{id}','listingPublishedStatus')->name('admin.listings.published.status.change')->permission('user-listing-published-status-change');
            Route::post('/status/{id}','changeStatus')->name('admin.listings.status.change')->permission('user-listing-status-change');
            Route::get('/search','searchListing')->name('admin.listings.search');
            Route::get('/paginate','paginate')->name('admin.listings.paginate');
            Route::post('/delete/{id}','listingDelete')->name('admin.listings.delete')->permission('user-listing-delete');
            Route::post('/bulk-action','bulkAction')->name('admin.listing.bulk.action')->permission('user-listing-bulk-delete');
        });

        // all guest listings
        Route::controller(\App\Http\Controllers\Backend\AdminGuestListingManageController::class)->group(function () {
            Route::get('/guest/all-listings','all_guest_listings')->name('admin.guest.all.listings')->permission('guest-listing-list');
            Route::get('/guest/search','searchListingGuest')->name('admin.guest.listings.search');
            Route::get('/guest/paginate','paginateGuest')->name('admin.guest.listings.paginate');
            Route::post('/guest-all/approved','guestListingsAllApproved')->name('admin.listings.guest.all.approved')->permission('guest-listing-all-approved');
            Route::post('/guest/delete/{id}','listingGuestDelete')->name('admin.guest.listings.delete')->permission('guest-listing-delete');
            Route::post('/guest/bulk-action','bulkGuestAction')->name('admin.guest.listing.bulk.action')->permission('guest-listing-bulk-delete');
        });

        // all admin listings
        Route::controller(\App\Http\Controllers\Backend\AdminListingController::class)->group(function () {
            Route::get('all','adminAllListings')->name('admin.all.listings')->permission('admin-listing-list');
            Route::match(['get','post'],'add','adminAddListing')->name('admin.add.new.listing')->permission('admin-listing-add');
            Route::match(['get','post'],'/admin-edit-listing/{id?}','adminEditListing')->name('admin.edit.listing')->permission('admin-listing-edit');
            Route::get('/admin-search','adminSearchListing')->name('admin.search.listings');
            Route::get('/admin-paginate','adminPaginate')->name('admin.paginate.listings');
            Route::post('/admin-delete/{id}','adminListingDelete')->name('admin.delete.listings')->permission('admin-listing-delete');
            Route::post('/admin-bulk-action','bulkAction')->name('admin.bulk.action.listing')->permission('admin-listing-bulk-delete');
            Route::post('/admin-published/{id}','adminListingPublishedStatus')->name('admin.listings.published.status.change.by')->permission('admin-listing-published-status-change');
            Route::post('/admin-status/{id}','adminChangeStatus')->name('admin.listings.status.change.by')->permission('admin-listing-status-change');
        });

        // listings report reasons
        Route::controller(\App\Http\Controllers\Backend\ReportReasonController::class)->group(function () {
            Route::match(['get','post'],'report/reason/all','all_reason')->name('admin.report.reason.all')->permission('report-reason-list');
            Route::post('report/reason/edit-reason','edit_reason')->name('admin.report.reason.edit')->permission('report-reason-edit');
            Route::post('report/reason/delete/{id}','delete_reason')->name('admin.report.reason.delete')->permission('report-reason-delete');
            Route::post('report/reason/bulk-action', 'bulk_action_reason')->name('admin.report.reason.delete.bulk.action')->permission('report-reason-bulk-delete');
            Route::get('report/reason/paginate/data', 'pagination')->name('admin.report.reason.paginate.data');
            Route::get('report/reason/search', 'search_reason')->name('admin.report.reason.search');
        });

        // listings report reasons
        Route::controller(\App\Http\Controllers\Backend\ListingReportController::class)->group(function () {
            Route::match(['get','post'],'report/all','all_report')->name('admin.listing.report.all')->permission('listing-report-list');
            Route::post('report/edit-report','edit_report')->name('admin.listing.report.edit')->permission('listing-report-edit');
            Route::post('report/delete/{id}','delete_report')->name('admin.listing.report.delete')->permission('listing-report-delete');
            Route::post('report/bulk-action', 'bulk_action_report')->name('admin.listing.report.delete.bulk.action')->permission('listing-report-bulk-delete');
            Route::get('report/paginate/data', 'pagination')->name('admin.listing.report.paginate.data');
            Route::get('report/search', 'search_report')->name('admin.listing.report.search');
        });
    });



    //user manage
    Route::group(['prefix' => 'user'],function(){
        Route::controller(\App\Http\Controllers\Backend\UserManageController::class)->group(function () {
            Route::match(['get','post'],'add-user','add_user')->name('admin.user.add')->permission('user-add');
            Route::get('all-users','all_users')->name('admin.user.all')->permission('user-list');
            Route::get('paginate/data/user', 'user_pagination')->name('admin.user.paginate.data');
            Route::get('search/user', 'search_user')->name('admin.user.search');
            Route::post('edit-user-info','edit_info')->name('admin.user.info.edit')->permission('user-edit');
            Route::post('change-user-password','change_password')->name('admin.user.password.change')->permission('user-password-change');

            Route::post('identity-details','identity_details')->name('admin.user.identity.details');
            Route::post('identity-verify/status','identity_verify_status')->name('admin.user.identity.verify.status')->permission('user-verify-status');
            Route::post('identity-verify/decline','identity_verify_decline')->name('admin.user.identity.verify.decline')->permission('user-verify-decline');

            Route::post('change-user-active-inactive-status/{id}','change_status')->name('admin.user.status')->permission('user-status-change');
            Route::post('delete/{id}','delete_user')->name('admin.user.delete')->permission('user-delete');
            Route::post('permanent-delete/{user_id}','permanent_delete')->name('admin.user.permanent.delete')->permission('user-permanent-delete');
            Route::match(['get','post'],'user-restore/{id?}','user_restore')->name('admin.user.restore');
            Route::get('paginate/delete/data', 'pagination_delete_user')->name('admin.user.paginate.delete.data');
            Route::get('delete/search-user', 'search_delete_user')->name('admin.user.delete.search');

            Route::get('verification-request','verification_requests')->name('admin.user.verification.request');
            Route::get('verification-request/paginate/data', 'verification_request_pagination')->name('admin.user.identity.request.paginate.data');
            Route::get('verification-request/search-user', 'verification_request_search_user')->name('admin.user.identity.request.search');
            Route::post('disable-2-factor-authentication/{id}','disable_2fa')->name('admin.user.disable._2fa');
            Route::post('verify-user-email/{id}','verify_user_email')->name('admin.user.verify.email');

            Route::get('deactivated/users-all', 'user_deactivated_all')->name('admin.user.deactivated.all')->permission('user-deactivated-list');
            Route::get('paginate/deactivated-user', 'user_deactivated_pagination')->name('admin.user.paginate.deactivated');
            Route::get('search/deactivated-user', 'search_deactivated_user')->name('admin.user.search.deactivated');
        });
    });


    /*------------------ ADVERTISEMENT ROUTE MANAGE --------------*/
    Route::group(['prefix'=>'advertisement'],function(){
        Route::get('/index', [AdvertisementController::class, 'index'])->name('admin.advertisement')->permission('advertisement-list');
        Route::get('/new',[AdvertisementController::class, 'new_advertisement'])->name('admin.advertisement.new')->permission('advertisement-add');
        Route::post('/store',[AdvertisementController::class, 'store_advertisement'])->name('admin.advertisement.store');
        Route::get('/edit/{id}',[AdvertisementController::class, 'edit_advertisement'])->name('admin.advertisement.edit')->permission('advertisement-edit');
        Route::post('/update/{id}',[AdvertisementController::class, 'update_advertisement'])->name('admin.advertisement.update');
        Route::post('/delete/{id}',[AdvertisementController::class, 'delete_advertisement'])->name('admin.advertisement.delete')->permission('advertisement-delete');
        Route::post('/bulk-action', [AdvertisementController::class, 'bulk_action'] )->name('admin.advertisement.bulk.action');
        Route::get('/search',[AdvertisementController::class, 'search_advertisement'])->name('admin.advertisement.search');
        Route::get('/paginate',[AdvertisementController::class, 'advertisement_paginate'])->name('admin.advertisement.paginate');
        Route::post('/change-status/{id}',[AdvertisementController::class, 'changeStatus'])->name('admin.advertisement.status')->permission('advertisement-status-change');
    });

    /*------------------ ADMIN CATEGORY MANAGE --------------*/
        Route::prefix('category')->group(function (){
            Route::get('/index',[CategoryController::class, 'index'])->name('admin.category')->permission('category-list');
            Route::match(['get','post'],'/add-new-category',[CategoryController::class, 'addNewCategory'])->name('admin.category.new')->permission('category-add');
            Route::match(['get','post'],'/edit-category/{id?}',[CategoryController::class, 'editCategory'])->name('admin.category.edit')->permission('category-edit');
            Route::post('/change-status/{id}',[CategoryController::class, 'changeStatus'])->name('admin.category.status')->permission('category-status-change');
            Route::post('/delete/{id}',[CategoryController::class, 'deleteCategory'])->name('admin.category.delete')->permission('category-delete');
            Route::post('/bulk-action', [CategoryController::class, 'bulkAction'])->name('admin.category.bulk.action')->permission('category-bulk-delete');
            Route::get('/search',[CategoryController::class, 'searchCategory'])->name('admin.category.search');
            Route::get('/paginate',[CategoryController::class, 'paginate'])->name('admin.category.paginate');
        });

        /*------------------ ADMIN SUBCATEGORY MANAGE --------------*/
        Route::prefix('subcategory')->group(function (){
            Route::get('/index',[SubCategoryController::class, 'index'])->name('admin.subcategory')->permission('subcategory-list');
            Route::match(['get','post'],'/add-new-subcategory',[SubcategoryController::class, 'addNewSubcategory'])->name('admin.subcategory.new')->permission('subcategory-add');
            Route::match(['get','post'],'/edit-subcategory/{id?}',[SubcategoryController::class, 'editSubcategory'])->name('admin.subcategory.edit')->permission('subcategory-edit');
            Route::post('/change-status/{id}',[SubcategoryController::class, 'changeStatus'])->name('admin.subcategory.status')->permission('subcategory-status-change');
            Route::post('/delete/{id}',[SubcategoryController::class, 'deleteSubcategory'])->name('admin.subcategory.delete')->permission('subcategory-delete');
            Route::post('/bulk-action', [SubcategoryController::class, 'bulkAction'])->name('admin.subcategory.bulk.action')->permission('subcategory-bulk-delete');
            Route::get('/search',[SubcategoryController::class, 'searchSubCategory'])->name('admin.subcategory.search');
            Route::get('/paginate',[SubcategoryController::class, 'paginate'])->name('admin.subcategory.paginate');
        });

        /*------------------ ADMIN SUBCATEGORY MANAGE --------------*/
        Route::prefix('child-category')->group(function (){
            Route::get('/index',[ChildCategoryController::class, 'index'])->name('admin.child.category')->permission('child-category-list');
            Route::match(['get','post'],'/add-new-child-category',[ChildCategoryController::class, 'addNewChildCategory'])->name('admin.child.category.new')->permission('child-category-add');
            Route::match(['get','post'],'/edit-child-category/{id?}',[ChildCategoryController::class, 'editChildCategory'])->name('admin.child.category.edit')->permission('child-category-edit');
            Route::post('/change-status/{id}',[ChildCategoryController::class, 'changeStatus'])->name('admin.child.category.status')->permission('child-category-status-change');
            Route::post('/delete/{id}',[ChildCategoryController::class, 'deleteChildCategory'])->name('admin.child.category.delete')->permission('child-category-delete');
            Route::post('/bulk-action', [ChildCategoryController::class, 'bulkAction'])->name('admin.child.category.bulk.action')->permission('child-category-bulk-delete');
            Route::get('/search',[ChildCategoryController::class, 'searchChildCategory'])->name('admin.child.category.search');
            Route::get('/paginate',[ChildCategoryController::class, 'paginate'])->name('admin.child.category.paginate');

            // get sub category for select
            Route::post('/admin-get-dependent-subcategory',[ChildCategoryController::class,'getSubcategory'])->name('admin.select.subcategory');
            Route::get('/get-subcategory-by-category',[ChildCategoryController::class,'getSubCategoryByCategoryId'])->name('admin.get.subcategory.by.category');
        });

        /*------------------ ADMIN PAGE MANAGE --------------*/
        Route::prefix('page-builder')->group(function (){
            Route::post('/update', [PageBuilderController::class, 'updateAddonContent'])->name('admin.page.builder.update');
            Route::post('/new', [PageBuilderController::class, 'storeNewAddonContent'])->name('admin.page.builder.new');
            Route::post('/delete', [PageBuilderController::class, 'delete'])->name('admin.page.builder.delete');
            Route::post('/update-order', [PageBuilderController::class, 'updateAddonOrder'])->name('admin.page.builder.update.addon.order');
            Route::post('/get-admin-markup', [PageBuilderController::class, 'getAdminPanelAddonMarkup'])->name('admin.page.builder.get.addon.markup');
        });

        /*------------------ ADMIN DYNAMIC Dynamic PAGE ROUTES --------------*/
        Route::prefix('dynamic-page')->group(function (){
            Route::get('/all',[PagesController::class, 'index'])->name('admin.page')->permission('dynamic-page-list');
            Route::get('/new',[PagesController::class, 'newPage'])->name('admin.page.new')->permission('dynamic-page-add');
            Route::post('/new',[PagesController::class, 'storeNewPage']);
            Route::get('/edit/{id}',[PagesController::class, 'editPage'])->name('admin.page.edit')->permission('dynamic-page-edit');
            Route::post('/update/{id}',[PagesController::class, 'updatePage'])->name('admin.page.update');
            Route::post('/delete/{id}',[PagesController::class, 'deletePage'])->name('admin.page.delete')->permission('dynamic-page-delete');
            Route::post('/delete/lang/all/{id}',[PagesController::class, 'deletePageLangAll'])->name('admin.page.delete.lang.all');
            Route::post('/bulk-action',[PagesController::class, 'bulkAction'])->name('admin.page.bulk.action')->permission('dynamic-page-bulk-delete');

            Route::get('/search',[PagesController::class, 'searchPage'])->name('admin.page.search');
            Route::get('/paginate',[PagesController::class, 'paginate'])->name('admin.page.paginate');
        });


        /*------------------ ADMIN PAGE BUILDER ROUTES --------------*/
        Route::group(['prefix' => 'page-builder','middleware' => 'auth:admin','setlang'],function () {
            Route::get('/home-page', [PageBuilderController::class, 'homePageBuilder'])->name('admin.home.page.builder');
            Route::post('/home-page', [PageBuilderController::class, 'updateHomePageBuilder']);
            Route::get('/about-page', [PageBuilderController::class, 'aboutPageBuilder'])->name('admin.about.page.builder');
            Route::post('/about-page', [PageBuilderController::class, 'updateAboutPageBuilder']);
            Route::get('/contact-page', [PageBuilderController::class, 'contactPageBuilder'])->name('admin.contact.page.builder');
            Route::post('/contact-page', [PageBuilderController::class, 'updateContactPageBuilder']);
            Route::get('/dynamic-page/{type}/{id}', [PageBuilderController::class, 'dynamicPageBuilder'])->name('admin.dynamic.page.builder');
            Route::post('/dynamic-page', [PageBuilderController::class, 'updateDynamicPageBuilder'])->name('admin.dynamic.page.builder.store');
        });


        /*------------------ ADMIN Google Map SETTINGS  --------------*/
        Route::prefix('map-settings')->group(function (){
            Route::get('/add-page', [MapSettings::class, 'addMapSettings'])->name('admin.map.settings.page')->permission('google-map-settings');
            Route::post('/add-page', [MapSettings::class, 'UpdateMapSettings']);
        });

       /*------------------ ADMIN Appearance SETTINGS  --------------*/
        Route::prefix('appearance-settings')->group(function (){

            //Navbar Global Variant
            Route::get('/global-variant-navbar', [GeneralSettingsController::class, 'globalVariantNavbar'])->name('admin.general.global.variant.navbar')->permission('navbar-global-variant');
            Route::post('/global-variant-navbar',[GeneralSettingsController::class, 'updateGlobalVariantNavbar']);

            //Footer Global Variant
            Route::get('/global-variant-footer',[GeneralSettingsController::class, 'globalVariantFooter'])->name('admin.general.global.variant.footer')->permission('footer-global-variant');
            Route::post('/global-variant-footer',[GeneralSettingsController::class, 'updateGlobalVariantFooter']);

            // Color Settings
            Route::get('/color-settings',[GeneralSettingsController::class, 'colorSettings'])->name('admin.general.color.settings')->permission('color-settings');
            Route::post('/color-settings',[GeneralSettingsController::class, 'updateColorSettings']);

            // Typography Settings
            Route::get('/typography-settings',[CustomFontController::class, 'typographySettings'])->name('admin.general.typography.settings')->permission('typography-settings');
            Route::post('/typography-settings',[CustomFontController::class, 'updateTypographySettings']);
            Route::post('typography-settings/single',[CustomFontController::class, 'getSingleFontVariant'])->name('admin.general.typography.single')->permission('typography-single-settings');
            Route::post('typography/custom/font/file',[CustomFontController::class, 'addCustomFont'])->name('admin.custom.font.add')->permission('font-add-settings');
            Route::post('typography/custom-font/single',[CustomFontController::class, 'getCustomSingleFont'])->name('admin.custom.typography.single');
            Route::post('typography/custom/font/css/update',[CustomFontController::class, 'updateCssCustomFont'])->name('admin.custom.font.css.update');
            Route::post('typography/custom-font/delete/{id}',[CustomFontController::class, 'deleteFontFile'])->name('admin.custom.delete.font.file')->permission('custom-font-delete');
            Route::post('/typography/change-status/{id}',[CustomFontController::class, 'changeStatusCustomFont'])->name('admin.custom.font.status')->permission('custom-font-status-change');
            Route::post('/typography/custom-font-heading/change-status/{id}',[CustomFontController::class, 'changeStatusCustomFontHeading'])->name('admin.custom.heading.font.status');


            //widgets manage
            Route::get('/widgets',[WidgetsController::class, 'index'])->name('admin.widgets')->permission('widgets-list');
            Route::post('/widgets/create',[WidgetsController::class, 'newWidget'])->name('admin.widgets.new')->permission('widgets-add');
            Route::post('/widgets/markup',[WidgetsController::class, 'widgetMarkup'])->name('admin.widgets.markup');
            Route::post('/widgets/update',[WidgetsController::class, 'updateWidget'])->name('admin.widgets.update');
            Route::post('/widgets/update/order',[WidgetsController::class, 'updateOrderWidget'])->name('admin.widgets.update.order');
            Route::post('/widgets/delete',[WidgetsController::class, 'deleteWidget'])->name('admin.widgets.delete')->permission('widgets-delete');

            //MENU MANAGE
            Route::get('/menu',[MenuController::class, 'index'])->name('admin.menu')->permission('menu-list');
            Route::post('/new-menu',[MenuController::class, 'storeNewMenu'])->name('admin.menu.new')->permission('menu-add');
            Route::get('/menu-edit/{id}',[MenuController::class, 'editMenu'])->name('admin.menu.edit')->permission('menu-edit');
            Route::post('/menu-update/{id}',[MenuController::class, 'updateMenu'])->name('admin.menu.update');
            Route::post('/menu-delete/{id}',[MenuController::class, 'deleteMenu'])->name('admin.menu.delete')->permission('menu-delete');
            Route::post('/menu-default/{id}',[MenuController::class, 'setDefaultMenu'])->name('admin.menu.default');
            Route::post('/mega-menu',[MenuController::class, 'megaMenuItemSelectMarkup'])->name('admin.mega.menu.item.select.markup');

            // form builder
            Route::controller(\App\Http\Controllers\Backend\FormBuilderController::class)->group(function () {
                Route::group(['prefix' => 'form'],function(){
                    Route::match(['get','post'],'/all','form')->name('admin.form')->permission('form-builder-list');
                    Route::get('/form-edit/{id}','edit_form')->name('admin.form.edit')->permission('form-builder-edit');
                    Route::post('/form-update/{id?}','update_form')->name('admin.form.update');
                    Route::post('/form-delete/{id}','delete_form')->name('admin.form.delete')->permission('form-builder-delete');
                    Route::post('/bulk-action', 'bulk_action')->name('admin.delete.bulk.action.form')->permission('form-builder-bulk.delete');
                });
            });

            // media upload
            Route::get('/media-upload/page',[MediaUploadController::class, 'allUploadMediaImagesForPage'])->name('admin.upload.media.images.page')->permission('media-upload');
            Route::post('/media-upload/delete',[MediaUploadController::class, 'deleteUploadMediaFile'])->name('admin.upload.media.file.delete')->permission('media-upload-delete');

            //404 page manage
            Route::get('404-page-manage',[Manage404PageController::class, 'error404pageSettings'])->name('admin.404.page.settings')->permission('404-page-settings');
            Route::post('404-page-manage',[Manage404PageController::class, 'update404PageSettings']);

            // maintains page
            Route::get('/maintains-page',[MaintainsPageController::class, 'maintainsPageSettings'])->name('admin.maintains.page.settings')->permission('maintains-page-settings');
            Route::post('/maintains-page-update',[MaintainsPageController::class, 'updateMaintainsPageSettings'])->name('admin.maintains.page.update.settings');

        });

        /*------------------ ADMIN NOTICE SETTINGS  --------------*/
         Route::controller(AdminNotificationController::class)->group(function () {
            Route::prefix('notification')->group(function (){
                Route::get('/all','all_notification')->name('admin.notification.all')->permission('notifications-list');
                Route::post('all/read','read_notification')->name('admin.notification.read');
                Route::get('search-notification', 'search_notification')->name('admin.notification.search');
                Route::get('paginate/data', 'pagination')->name('admin.notification.paginate.data');
            });
        });

        /*------------------ ADMIN NOTICE SETTINGS  --------------*/
        Route::prefix('notice')->group(function (){
            Route::get('/all',[NoticeController::class, 'allNotice'])->name('admin.all.notice')->permission('notice-list');
            Route::get('/add/page',[NoticeController::class, 'addNoticePage'])->name('admin.add.notice.page')->permission('notice-add');
            Route::post('/add',[NoticeController::class, 'addNotice'])->name('admin.add.notice');
            Route::get('/edit/{id}',[NoticeController::class, 'noticeEdit'])->name('admin.notice.edit')->permission('notice-edit');
            Route::post('/update',[NoticeController::class, 'noticeUpdate'])->name('admin.notice.update');
            Route::post('/delete-user/{id}',[NoticeController::class, 'newNoticeDelete'])->name('admin.delete.notice')->permission('notice-delete');
            Route::post('/status/{id}',[NoticeController::class, 'changeStatus'])->name('admin.notice.status')->permission('notice-status-change');
            Route::get('/search',[NoticeController::class, 'searchNotice'])->name('admin.notice.search');
            Route::get('/paginate',[NoticeController::class, 'paginate'])->name('admin.notice.paginate');
        });

        /*------------------ ADMIN ALL PAGE SETTINGS  --------------*/
        Route::prefix('page-settings')->group(function (){
            Route::match(['get', 'post'], '/register-page', [PageSettingsController::class, 'loginRegisterPageSettings'])->name('admin.login.register.page.settings')->permission('login-register-page-settings');
            Route::match(['get', 'post'], '/listing-create-page/settings', [PageSettingsController::class, 'listingCreateSettings'])->name('admin.listing.create.settings')->permission('listing-create-page-settings');
            Route::match(['get', 'post'], '/listing-details-page/settings', [PageSettingsController::class, 'listingDetailsSettings'])->name('admin.listing.details.settings')->permission('listing-details-page-settings');
            Route::match(['get', 'post'], '/guest-listing/settings', [PageSettingsController::class, 'listingGuestSettings'])->name('admin.listing.guest.settings')->permission('listing-guest-page-settings');
            Route::match(['get', 'post'], '/user-public-profile/settings', [PageSettingsController::class, 'userPublicProfileSettings'])->name('admin.user.public.profile.settings')->permission('user-public-profile-page-settings');
        });

        /*------------------ EMAIL SETTINGS MANAGE --------------*/
     Route::prefix('email-settings')->group(function (){
            Route::post('/basic-settings',[EmailSettingsController::class, 'updateEmailSettings']);
            //smtp settings
            Route::get('/smtp',[EmailSettingsController::class, 'smtpSettings'])->name('admin.email.smtp.settings')->permission('smtp-settings');
            Route::post('/update-smtp',[EmailSettingsController::class, 'updateSmtpSettings'])->name('admin.email.smtp.update.settings');
            Route::post('/test-smtp', [EmailSettingsController::class, 'testSmtpSettings'])->name('admin.email.smtp.settings.test');

            //All Email  Templates
            Route::get('/all-email-templates', [EmailTemplateController::class, 'allEmailTemplates'])->name('admin.email.template.all');
            Route::match(['get', 'post'], '/global-template', [EmailTemplateController::class, 'globalEmailTemplateSettings'])->name('admin.email.global.template');
            Route::match(['get', 'post'], '/user/register/template', [EmailTemplateController::class, 'userRegisterTemplate'])->name('admin.email.user.register.template');
            Route::match(['get', 'post'], '/user/identity-verification/template', [EmailTemplateController::class, 'userIdentityVerificationTemplate'])->name('admin.email.user.identity.verification.template');
            Route::match(['get', 'post'], '/user/email-verify/template', [EmailTemplateController::class, 'userEmailVerifyTemplate'])->name('admin.email.user.verify.template');
            Route::match(['get', 'post'], '/user/wallet-deposit/template', [EmailTemplateController::class, 'userWalletDepositTemplate'])->name('admin.email.user.wallet.deposit.template');
            Route::match(['get', 'post'], '/user/new-listing-approval/template', [EmailTemplateController::class, 'userNewListingApprovalTemplate'])->name('admin.email.user.new.listing.approval.template');
            Route::match(['get', 'post'], '/user/new-listing-publish/template', [EmailTemplateController::class, 'userNewListingPublishTemplate'])->name('admin.email.user.new.listing.publish.template');
            Route::match(['get', 'post'], '/user/new-listing-unpublished/template', [EmailTemplateController::class, 'userNewListingUnpublishedTemplate'])->name('admin.email.user.new.listing.unpublished.template');
            Route::match(['get', 'post'], '/user/guest-listing-add/template', [EmailTemplateController::class, 'userGuestAddNewListingTemplate'])->name('admin.email.user.guest.add.listing.template');
            Route::match(['get', 'post'], '/user/guest-listing-approve/template', [EmailTemplateController::class, 'userGuestApproveListingTemplate'])->name('admin.email.user.guest.approve.listing.template');
            Route::match(['get', 'post'], '/user/guest-listing-publish/template', [EmailTemplateController::class, 'userGuestPublishListingTemplate'])->name('admin.email.user.guest.publish.listing.template');
        });


        /*------------------ GENERAL SETTINGS MANAGE --------------*/
        Route::prefix('general-settings')->group(function (){
            Route::get('/reading',[GeneralSettingsController::class, 'reading'])->name('admin.general.reading')->permission('reading-settings');
            Route::post('/reading',[GeneralSettingsController::class, 'updateReading']);

            Route::get('/site-identity',[GeneralSettingsController::class, 'siteIdentity'])->name('admin.general.site.identity')->permission('site-identity-settings');
            Route::post('/site-identity',[GeneralSettingsController::class, 'updateSiteIdentity']);

            Route::get('/basic-settings',[GeneralSettingsController::class, 'basicSettings'])->name('admin.general.basic.settings')->permission('basic-settings');
            Route::post('/basic-settings',[GeneralSettingsController::class, 'updateBasicSettings']);

            Route::get('/seo-settings',[GeneralSettingsController::class, 'seoSettings'])->name('admin.general.seo.settings')->permission('seo-settings');
            Route::post('/seo-settings',[GeneralSettingsController::class, 'updateSeoSettings']);

            Route::get('/scripts',[GeneralSettingsController::class, 'scriptsSettings'])->name('admin.general.scripts.settings')->permission('scripts-settings');
            Route::post('/scripts',[GeneralSettingsController::class, 'updateScriptsSettings']);

            //custom css
            Route::get('/custom-css',[GeneralSettingsController::class, 'customCssSettings'])->name('admin.general.custom.css')->permission('custom-css-settings');
            Route::post('/custom-css',[GeneralSettingsController::class, 'updateCustomCssSettings']);

            //custom js
            Route::get('/custom-js',[GeneralSettingsController::class, 'customJsSettings'])->name('admin.general.custom.js')->permission('custom-js-settings');
            Route::post('/custom-js',[GeneralSettingsController::class, 'updateCustomJsSettings']);

            //* sitemap settings
            Route::get('/sitemap-settings', [GeneralSettingsController::class, 'sitemapSettings'])->name('admin.general.sitemap.settings')->permission('sitemap-settings');
            Route::post('/sitemap-settings', [GeneralSettingsController::class, 'updateSitemapSettings']);
            Route::post('/sitemap-settings/delete', [GeneralSettingsController::class, 'deleteSitemapSettings'] )->name('admin.general.sitemap.settings.delete')->permission('sitemap-delete');

            //gdpr-settings
            Route::get('/gdpr-settings',[GeneralSettingsController::class, 'gdprSettings'])->name('admin.general.gdpr.settings')->permission('gdpr-settings');
            Route::post('/gdpr-settings',[GeneralSettingsController::class, 'updateGdprCookieSettings']);

            //license-setting
            Route::get('/license-setting',[GeneralSettingsController::class, 'licenseSettings'])->name('admin.general.license.settings')->permission('license-setting');
            Route::post('/license-setting',[GeneralSettingsController::class, 'updateLicenseSettings']);

            //cache settings
            Route::get('/cache-settings',[GeneralSettingsController::class, 'cacheSettings'])->name('admin.general.cache.settings')->permission('cache-setting');
            Route::post('/cache-settings',[GeneralSettingsController::class, 'updateCacheSettings']);

            //database upgrade
            Route::get('/database-upgrade', [GeneralSettingsController::class, 'databaseUpgrade'])->name('admin.general.database.upgrade')->permission('database-upgrade-setting');
            Route::post('/database-upgrade', [GeneralSettingsController::class, 'databaseUpgradePost']);

            Route::post('/license-setting-verify', [GeneralSettingsController::class, 'licenseKeyGenerate'])->name('admin.general.license.key.generate')->permission('license-key-generate');
            Route::get('/update-check', [GeneralSettingsController::class, 'updateVersionCheck'])->name('admin.general.update.version.check')->permission('update-version-check');
            Route::post('/download-update/{productId}/{tenant}', [GeneralSettingsController::class, 'updateDownloadLatestVersion'])->name('admin.general.update.download.settings');
            Route::get('/software-update-setting', [GeneralSettingsController::class, 'softwareUpdateCheckSettings'])->name('admin.general.software.update.settings')->permission('software-update-settings');
        });

        //language
        Route::get('/languages',[LanguageController::class, 'index'])->name('admin.languages')->permission('languages-list');
        Route::get('/languages/words/all/{id}',[LanguageController::class, 'allEditWords'])->name('admin.languages.words.all')->permission('languages-words-edit');
        Route::post('/languages/words/update/{id}',[LanguageController::class, 'updateWords'])->name('admin.languages.words.update');
        Route::post('/languages/new',[LanguageController::class, 'store'])->name('admin.languages.new')->permission('languages-add');
        Route::post('/languages/update',[LanguageController::class, 'update'])->name('admin.languages.update');
        Route::post('/languages/delete/{id}',[LanguageController::class, 'delete'])->name('admin.languages.delete')->permission('languages-delete');
        Route::post('/languages/default/{id}',[LanguageController::class, 'makeDefault'])->name('admin.languages.default');
        Route::post('/languages/clone',[LanguageController::class, 'cloneLanguages'])->name('admin.languages.clone')->permission('languages-clone');
        Route::post('/languages/add-new-word',[LanguageController::class, 'addNewWords'])->name('admin.languages.add.new.word');
        Route::post('/languages/regenerate-source-text',[LanguageController::class, 'regenerateSourceText'])->name('admin.languages.regenerate.source.texts');
});


    // media upload routes end
    Route::post('/media-upload/all', [MediaUploadController::class,'allUploadMediaFile'])->name('admin.upload.media.file.all');
    Route::post('/media-upload', [MediaUploadController::class,'uploadMediaFile'])->name('admin.upload.media.file');
    Route::post('/media-upload/alt', [MediaUploadController::class,'altChangeUploadMediaFile'])->name('admin.upload.media.file.alt.change');
    // media upload routes for restrict user in demo mode
    Route::post('/media-upload/loadmore',  [MediaUploadController::class,'getImageForLoadmore'])->name('admin.upload.media.file.loadmore');

