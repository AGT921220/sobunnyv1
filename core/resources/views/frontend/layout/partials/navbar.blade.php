
@inject('categoriesService', 'App\Services\CategoriesServices') <!-- Inyecta la clase o servicio -->

@php
    $navbar_variant = !is_null(get_navbar_style()) ? get_navbar_style() : '02';
    $genders = $categoriesService->getAllGenders->__invoke();
    $ethnicities = $categoriesService->getAllEthnicitys->__invoke();
    $ages = $categoriesService->getAllAges->__invoke();
    $breasts = $categoriesService->getAllBreasts->__invoke();
    $caters = $categoriesService->getAllCaters->__invoke();
    $bodyTypes = $categoriesService->getAllBodyTypes->__invoke();
    $eyeColors = $categoriesService->getAllEyeColors->__invoke();
    $hairColors = $categoriesService->getAllHairColors->__invoke();
    $serviceTypes = $categoriesService->getAllServiceTypes->__invoke();
    $servicings = $categoriesService->getAllServicings->__invoke();
    $heights = $categoriesService->getAllHeighs->__invoke();
    $current_page_url = URL::current();
    $cities = Modules\CountryManage\app\Models\City::all();
@endphp
@include('frontend.layout.partials.navbar-variant.navbar-' . $navbar_variant)

