use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/calendar', [CalendarController::class, 'index']);   // ambil semua event
    Route::post('/calendar', [CalendarController::class, 'store']);  // tambah event
    Route::put('/calendar/{id}', [CalendarController::class, 'update']); // edit event
    Route::delete('/calendar/{id}', [CalendarController::class, 'destroy']); // hapus event
});
