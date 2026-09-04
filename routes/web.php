<?php

/**
 * Định nghĩa route
 * $router->get(URL, "Controller@method")
 * $router->post(URL, "Controller@method")
 *
 * {id} trong URL sẽ được truyền vào method dưới dạng tham số
 * theo đúng thứ tự xuất hiện.
 */

// ==== Client - Trang chủ & sản phẩm ====
$router->get("/", "PaintingController@index");
$router->get("/products/{id}", "PaintingController@show");

// ==== Client - Giỏ hàng (yêu cầu đăng nhập, xử lý qua JS/AJAX trả JSON) ====
$router->get("/cart", "CartController@index");
$router->post("/cart/add", "CartController@add");
$router->post("/cart/update", "CartController@update");
$router->post("/cart/delete", "CartController@delete");

// ==== Client - Địa chỉ giao hàng (yêu cầu đăng nhập) ====
$router->get("/addresses", "AddressController@index");
$router->get("/addresses/create", "AddressController@create");
$router->post("/addresses/create", "AddressController@store");
$router->get("/addresses/edit/{id}", "AddressController@edit");
$router->post("/addresses/edit/{id}", "AddressController@update");
$router->post("/addresses/delete", "AddressController@delete");
$router->post("/addresses/set-default", "AddressController@setDefault");

// ==== Auth (chung cho client) ====
$router->get("/login", "AuthController@showLogin");
$router->post("/login", "AuthController@login");
$router->get("/register", "AuthController@showRegister");
$router->post("/register", "AuthController@register");
$router->get("/logout", "AuthController@logout");

// ==== Admin - Categories (giữ nguyên, phần admin do thành viên khác phụ trách) ====
$router->get("/admin/categories", "CategoryController@index");
$router->get("/admin/categories/create", "CategoryController@create");
$router->post("/admin/categories/create", "CategoryController@store");
$router->get("/admin/categories/edit/{id}", "CategoryController@edit");
$router->post("/admin/categories/edit/{id}", "CategoryController@update");
$router->get("/admin/categories/delete/{id}", "CategoryController@delete");

// ==== Client - Đặt hàng & thanh toán (yêu cầu đăng nhập) ====
$router->get("/checkout", "OrderController@checkout");
$router->post("/checkout", "OrderController@store");
$router->get("/orders", "OrderController@index");
$router->get("/orders/{id}", "OrderController@show");
$router->get("/orders/{id}/pay", "OrderController@showPayment");
$router->post("/orders/{id}/pay", "OrderController@pay");

// ==== Admin - Đơn hàng ====
$router->get("/admin/orders", "AdminOrderController@index");
$router->get("/admin/orders/{id}", "AdminOrderController@show");
$router->post("/admin/orders/{id}/status", "AdminOrderController@updateStatus");
