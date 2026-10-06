<?php
use App\Controllers\AuthController;
use App\Controllers\EspecieController;
return [
    'login' => ['GET', AuthController::class, 'form', false],
    'login.submit' => ['POST', AuthController::class, 'login', false],
    'logout' => ['POST', AuthController::class, 'logout', true],
    'especies' => ['GET', EspecieController::class, 'index', true],
    'especies.create' => ['GET', EspecieController::class, 'create', true],
    'especies.store' => ['POST', EspecieController::class, 'store', true],
    'especies.edit' => ['GET', EspecieController::class, 'edit', true],
    'especies.update' => ['POST', EspecieController::class, 'update', true],
    'especies.destroy' => ['POST', EspecieController::class, 'destroy', true],
];
