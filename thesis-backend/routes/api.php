use App\Http\Controllers\Api\ArchiveController;

Route::get('/archives', [ArchiveController::class, 'index']);
Route::post('/archives', [ArchiveController::class, 'store']);