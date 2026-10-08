<?php
namespace App\Controllers;
use App\Http\View;
final class HomeController
{
    public function index(): void { View::render('home/index'); }
}
