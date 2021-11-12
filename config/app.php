<?php
function route(string $url = '') : string
{
    $url = str_replace('.', '/', ltrim($url, "/"));
    $app_url = rtrim(APP_URL, "/");
    return strtolower($app_url."/$url");
}

function url(string $url = '') : string
{
    $url = ltrim($url, "/");
    $app_url = rtrim(APP_URL, "/");
    return strtolower($app_url."/$url");
}

function asset(string $url) : string
{
    $url = ltrim($url, "/");
    $app_url = rtrim(APP_URL, "/");
    return strtolower($app_url."/assets/$url");
}

function view(string $content, array $data = []) {
    global $app;
    $app->template->render($content, $data);
}

function error_page(string $page = '_404', array $data = []) {
    view($page, $data);
    die;
}

function old(string $field) {
    global $app;
    return $app->request->input($field) ?? '';
}

function section(string $name, string $value = '') {
    global $app;
    $app->template->section($name, $value);
}

function endsection() {
    global $app;
    $app->template->end();
}

function layout(string $layout) {
    global $app;
    $app->template->layout($layout);
}

function register(string $name) {
    global $app;
    return $app->template->yield($name);
}

function encode_email_token(string $email)  : string
{
    return base64_encode(time().rand().'|'.$email);
}

function session_set($key, $value) {
    global $app;
    $app->session::set($key, $value);
}

function session_get($key) {
    global $app;
    return $app->session::get($key);
}

function session_remove($key) {
    global $app;
    $app->session::remove($key);
}

function session_has($key) : bool
{
    global $app;
    return $app->session::has($key);
}

function redirect($route = '') {
    header("Location: ".route($route));
    exit;
}

function priceVND($price) : string
{
    global $app;
    return $app->helper::priceVND($price);
}

function calculatorTime($start_time) : string
{
    global $app;
    return $app->helper::calculatorTime($start_time);
}

function upload_image($file = [], $folder = '') {
    global $app;
    return $app->helper::uploadImage($file, $folder);
}

function requestInput($name) {
    global $app;
    return $app->request->input($name);
}

function getMenuClient() {
    global $app;
    return $app->option->getMenuClient();
}