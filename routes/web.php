<?php


$router->get("/", "PaintingController@index");
$router->get("/products/{id}", "PaintingController@show");

$router->get("/cart", "CartController@index");
$router->post("/cart/add", "CartController@add");
$router->post("/cart/update", "CartController@update");
$router->post("/cart/delete", "CartController@delete");

$router->get("/addresses", "AddressController@index");
$router->get("/addresses/create", "AddressController@create");
$router->post("/addresses/create", "AddressController@store");
$router->get("/addresses/edit/{id}", "AddressController@edit");
$router->post("/addresses/edit/{id}", "AddressController@update");
$router->post("/addresses/delete", "AddressController@delete");
$router->post("/addresses/set-default", "AddressController@setDefault");

$router->get("/login", "AuthController@showLogin");
$router->post("/login", "AuthController@login");
$router->get("/register", "AuthController@showRegister");
$router->post("/register", "AuthController@register");
$router->get("/logout", "AuthController@logout");

$router->get("/admin/categories", "CategoryController@index");
$router->get("/admin/categories/create", "CategoryController@create");
$router->post("/admin/categories/create", "CategoryController@store");
$router->get("/admin/categories/edit/{id}", "CategoryController@edit");
$router->post("/admin/categories/edit/{id}", "CategoryController@update");
$router->get("/admin/categories/delete/{id}", "CategoryController@delete");

$router->get("/checkout", "OrderController@checkout");
$router->post("/checkout", "OrderController@store");
$router->get("/orders", "OrderController@index");
$router->get("/orders/{id}", "OrderController@show");
$router->get("/orders/{id}/pay", "OrderController@showPayment");
$router->post("/orders/{id}/pay", "OrderController@pay");

$router->get("/admin/orders", "AdminOrderController@index");
$router->get("/admin/orders/{id}", "AdminOrderController@show");
$router->post("/admin/orders/{id}/status", "AdminOrderController@updateStatus");

$router->get("/admin/dashboard", "AdminController@dashboard");
$router->get("/admin/login", "AuthController@showLogin");


$router->get("/admin/paintings", "AdminPaintingController@index");
$router->get("/admin/paintings/create", "AdminPaintingController@create");
$router->post("/admin/paintings/create", "AdminPaintingController@store");
$router->get("/admin/paintings/edit/{id}", "AdminPaintingController@edit");
$router->post("/admin/paintings/edit/{id}", "AdminPaintingController@update");
$router->get("/admin/paintings/delete/{id}", "AdminPaintingController@delete");

$router->get("/admin/users", "AdminUserController@index");
$router->get("/admin/users/create", "AdminUserController@create");
$router->post("/admin/users/create", "AdminUserController@store");
$router->get("/admin/users/edit/{id}", "AdminUserController@edit");
$router->post("/admin/users/edit/{id}", "AdminUserController@update");
$router->get("/admin/users/delete/{id}", "AdminUserController@delete");
$router->get("/admin/users/lock/{id}", "AdminUserController@lock");
$router->get("/admin/users/unlock/{id}", "AdminUserController@unlock");
$router->get("/admin/users/{id}", "AdminUserController@show");