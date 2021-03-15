
<?php

if (!function_exists('areActiveRoutes')) {
    function areActiveRoutes(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (\Illuminate\Support\Facades\Route::currentRouteName() == $route) return $output;
        }

    }
}
if (!function_exists('proxy')) {
    function proxy($route,$shop)
    {
        return str_replace(env('APP_URL'),$shop.'/a/landing',$route);
    }
}
?>
