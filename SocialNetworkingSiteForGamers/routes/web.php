<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InternalEventsController;
use App\Http\Controllers\AttachmentsController;
use App\Http\Controllers\AttachmentController;


Route::get('/', [HomeController::class,"index"]);
Route::get("/internal-events", [InternalEventsController::class,"index"]);
Route::get("/internal-events/edit/{id}", [InternalEventsController::class,"edit"]);
Route::post("/internal-events/update/{id}", [InternalEventsController::class,"update"]);
Route::get("/internal-events/create", [InternalEventsController::class,"create"]);
Route::post("/internal-events/add-to-db", [InternalEventsController::class,"addToDB"]);

Route::get('/internal-events/delete/{id}', [InternalEventsController::class, 'showDelete']);
Route::post('/internal-events/delete/{id}', [InternalEventsController::class, 'delete']);

Route::get   ('/attachments',            [AttachmentsController::class, 'index']);
Route::get   ('/attachments/create',     [AttachmentsController::class, 'create']);
Route::post  ('/attachments/add-to-db',  [AttachmentsController::class, 'addToDB']);
Route::get   ('/attachments/{id}/edit',  [AttachmentsController::class, 'edit']);
Route::post  ('/attachments/{id}/update',[AttachmentsController::class, 'update']);
Route::get('/attachments/delete/{id}', [AttachmentsController::class, 'showDelete']);
Route::post('/attachments/delete/{id}', [AttachmentsController::class, 'delete']);
