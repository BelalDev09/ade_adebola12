<?php

use App\Models\Review;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WEB\PersonController;
use App\Http\Controllers\WEB\ContactController;
use App\Http\Controllers\WEB\Admin\RoleController;
use App\Http\Controllers\WEB\Admin\SmtpController;
use App\Http\Controllers\WEB\Admin\UserController;
use App\Http\Controllers\WEB\CMS\WhoForController;
use App\Http\Controllers\WEB\Admin\ReviewController;
use App\Http\Controllers\WEB\NotificationController;
use App\Http\Controllers\WEB\Admin\SupportController;
use App\Http\Controllers\WEB\CMS\HowItWorkController;
use App\Http\Controllers\WEB\CMS\CmsContentController;
use App\Http\Controllers\WEB\Admin\DashboardController;
use App\Http\Controllers\WEB\CMS\HeroSectionController;
use App\Http\Controllers\WEB\CMS\MarketToolsController;
use App\Http\Controllers\WEB\Admin\PermissionController;
use App\Http\Controllers\WEB\CMS\TestimonialsController;
use App\Http\Controllers\WEB\Admin\ReviewReportController;
use App\Http\Controllers\WEB\Admin\AccountSettingController;

//test
Route::get('/test-gate', function () {
    dd(Gate::allows('anything-at-all'));
});

// Public Routes
Route::get('/', fn() => view('welcome'));
// Route::get('/dashboard', fn() => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');
// Login route
Route::get('/login', function () {
    return view('auth.login');
})->name('profile.login');
// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');
    Route::get('/dashboard/user-growth', [DashboardController::class, 'userGrowth'])
        ->middleware('permission:dashboard.view')
        ->name('backend.admin.userGrowth');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [PersonController::class, 'show'])
            ->middleware('permission:profile.view')
            ->name('show');
        Route::get('/settings', [PersonController::class, 'edit'])
            ->middleware('permission:profile.edit')
            ->name('edit');
        Route::patch('/', [PersonController::class, 'update'])
            ->middleware('permission:profile.edit')
            ->name('update');
        Route::post('/password', [PersonController::class, 'updatePassword'])
            ->middleware('permission:profile.edit')
            ->name('password');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // UserController;
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('users', [UserController::class, 'index'])
            ->middleware('permission:users.manage')
            ->name('users.index');
        // AJAX data route
        // Route::get('users/data', [UserController::class, 'data'])->name('users.data');
        // CRUD
        Route::post('users', [UserController::class, 'store'])
            ->middleware('permission:users.manage')
            ->name('users.store');
        Route::put('users/{user}', [UserController::class, 'update'])
            ->middleware('permission:users.manage')
            ->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])
            ->middleware('permission:users.manage')
            ->name('users.destroy');
        Route::post('users/assign-role', [RoleController::class, 'assignUserRole'])
            ->middleware('permission:roles.manage')
            ->name('users.roles.assign');
        Route::delete('users/{user}/roles/{role}', [RoleController::class, 'removeUserRole'])
            ->middleware('permission:roles.manage')
            ->name('users.roles.destroy');

        // Roles
        Route::get('roles', [RoleController::class, 'index'])
            ->middleware('permission:roles.manage')
            ->name('roles.index');
        Route::get('roles/create', [RoleController::class, 'create'])
            ->middleware('permission:roles.manage')
            ->name('roles.create');
        Route::post('roles', [RoleController::class, 'store'])
            ->middleware('permission:roles.manage')
            ->name('roles.store');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])
            ->middleware('permission:roles.manage')
            ->name('roles.edit');
        Route::put('roles/{role}', [RoleController::class, 'update'])
            ->middleware('permission:roles.manage')
            ->name('roles.update');
        Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])
            ->middleware('permission:roles.manage')
            ->name('roles.permissions');
        Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])
            ->middleware('permission:roles.manage')
            ->name('roles.permissions.update');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])
            ->middleware('permission:roles.manage')
            ->name('roles.destroy');
        Route::delete('roles/{role}/force', [RoleController::class, 'forceDestroy'])
            ->middleware('permission:roles.manage')
            ->name('roles.forceDestroy');

        // Permissions
        Route::get('permissions', [PermissionController::class, 'index'])
            ->middleware('permission:permissions.manage')
            ->name('permissions.index');
        Route::post('permissions', [PermissionController::class, 'store'])
            ->middleware('permission:permissions.manage')
            ->name('permissions.store');
        Route::put('permissions/{permission}', [PermissionController::class, 'update'])
            ->middleware('permission:permissions.manage')
            ->name('permissions.update');
        Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])
            ->middleware('permission:permissions.manage')
            ->name('permissions.destroy');

        // support route
        Route::get('support', [SupportController::class, 'index'])
            ->middleware('permission:support.manage')
            ->name('support.index');
        Route::get('support/data', [SupportController::class, 'data'])
            ->middleware('permission:support.manage')
            ->name('support.data');
        Route::get('support/create', [SupportController::class, 'create'])
            ->middleware('permission:support.manage')
            ->name('support.create');
        Route::post('support', [SupportController::class, 'store'])
            ->middleware('permission:support.manage')
            ->name('support.store');
        Route::get('support/{user}/edit', [SupportController::class, 'edit'])
            ->middleware('permission:support.manage')
            ->name('support.edit');
        Route::put('support/{user}', [SupportController::class, 'update'])
            ->middleware('permission:support.manage')
            ->name('support.update');
        Route::delete('support/{user}', [SupportController::class, 'destroy'])
            ->middleware('permission:support.manage')
            ->name('support.destroy');
    });


    // cms hero section
    Route::prefix('cms')->name('cms.')->middleware('permission:cms.manage')->group(function () {
        Route::get('/hero', [HeroSectionController::class, 'form'])->name('hero.form');
        Route::post('/hero', [HeroSectionController::class, 'store'])->name('hero.store');
        //     Route::get('/hero/{id}', [HeroSectionController::class, 'show'])
        // ->name('hero.show');

    });

    // Market Tools
    Route::prefix('hello')->name('cms.')->middleware('permission:cms.manage')->group(function () {
        Route::get('market-tools', [MarketToolsController::class, 'index'])->name('market-tools.index');
        Route::post('market-tools', [MarketToolsController::class, 'store'])->name('market-tools.store');
        Route::get('market-tools/{id}', [MarketToolsController::class, 'show'])->name('market-tools.show');
        Route::delete('market-tools/{id}', [MarketToolsController::class, 'delete'])->name('market-tools.destroy');
        Route::post('market-tools/status/{id}', [MarketToolsController::class, 'updateStatus'])->name('market-tools.status');
    });
    // CMS Pages Prefix
    Route::prefix('cms')->name('cms.')->middleware('permission:cms.manage')->group(function () {
        Route::get('/', [CmsContentController::class, 'index'])->name('index');
        Route::post('/', [CmsContentController::class, 'store'])->name('store');
        Route::get('{cms}', [CmsContentController::class, 'show'])->name('show');
        Route::delete('{cms}', [CmsContentController::class, 'destroy'])->name('destroy');
        Route::post('status/{cms}', [CmsContentController::class, 'statusToggle'])->name('status');
    });

    // How It Works
    Route::prefix('why')->name('cms.')->middleware('permission:cms.manage')->group(function () {
        // List
        Route::get('how-it-works', [HowItWorkController::class, 'form'])->name('how-it-works.form');
        // Store
        Route::post('how-it-works', [HowItWorkController::class, 'store'])->name('how-it-works.store');
        // Show
        Route::get('how-it-works/{id}', [HowItWorkController::class, 'show']);
        // Update
        Route::post('how-it-works/update', [HowItWorkController::class, 'update'])->name('how-it-works.update');
        // Delete
        Route::delete('how-it-works/{id}', [HowItWorkController::class, 'delete']);
        Route::post(
            '/how-it-works/image-delete/{id}',
            [HowItWorkController::class, 'deleteImage']
        )->name('how-it-works.image-delete');

        // Status toggle
        Route::post('how-it-works/status/{id}', [HowItWorkController::class, 'updateStatus']);
    });

    // Testimonials
    Route::prefix('what')->name('cms.')->middleware('permission:cms.manage')->group(function () {
        Route::get('testimonials', [TestimonialsController::class, 'form'])->name('testimonials.form');
        Route::post('testimonials/store', [TestimonialsController::class, 'store'])->name('testimonials.store');
    });
    // Who For Section
    Route::prefix('good')->name('cms.')->middleware('permission:cms.manage')->group(function () {
        Route::get('/who-for', [WhoForController::class, 'index'])->name('who-for.index');
        Route::post('/who-for/store', [WhoForController::class, 'store'])->name('who-for.store');
        Route::get('/who-for/{id}', [WhoForController::class, 'show'])->name('who-for.show');
        Route::post('/who-for/delete/{id}', [WhoForController::class, 'delete'])->name('who-for.delete');
        Route::post('/who-for/status/{id}', [WhoForController::class, 'updateStatus'])->name('who-for.status');
    });

    // review form db data
    Route::prefix('backend/admin')->name('backend.admin.')->group(function () {
        Route::get('/reviews', [ReviewController::class, 'index'])
            ->middleware('permission:reviews.manage')
            ->name('reviews.index');

        Route::get('/reviews/data', [ReviewController::class, 'data'])
            ->middleware('permission:reviews.manage')
            ->name('reviews.data');

        Route::get('/reviews/location/{locationId}', [ReviewController::class, 'locationReviews'])
            ->middleware('permission:reviews.manage')
            ->name('reviews.location');
        // reports
        Route::get('/reports', [ReviewReportController::class, 'index'])
            ->middleware('permission:reports.manage')
            ->name('reports.index');
        Route::get('/reports/data', [ReviewReportController::class, 'data'])
            ->middleware('permission:reports.manage')
            ->name('reports.data');
        // show page
        Route::get('/reports/{id}', [ReviewReportController::class, 'show'])
            ->middleware('permission:reports.manage')
            ->name('reports.show');

        //Approve / Reject
        Route::post('/reports/{id}/status', [ReviewReportController::class, 'updateStatus'])
            ->middleware('permission:reports.manage')
            ->name('reports.update-status');
    });

    // contact us
    Route::get('/contact', [ContactController::class, 'index'])
        ->middleware('permission:cms.manage')
        ->name('backend.admin.contact');
    Route::post('/contact', [ContactController::class, 'store'])
        ->middleware('permission:cms.manage')
        ->name('contact.store');

    // smtp
    Route::get('/admin/smtp', [SmtpController::class, 'index'])
        ->middleware('permission:settings.smtp')
        ->name('admin.smtp.index');
    Route::post('/admin/smtp', [SmtpController::class, 'store'])
        ->middleware('permission:settings.smtp')
        ->name('admin.smtp.store');

    // account setting
    Route::prefix('admin')->name('backend.admin.')->group(function () {
        Route::get('/setting', [AccountSettingController::class, 'edit'])
            ->middleware('permission:settings.account')
            ->name('account.edit');

        Route::put('/setting', [AccountSettingController::class, 'update'])
            ->middleware('permission:settings.account')
            ->name('account.update');
    });
    // Notifications
    Route::get('/notification/read/{id}', [NotificationController::class, 'read'])
        ->name('notifications.read');

    Route::get('/notification/mark-all-read', [NotificationController::class, 'markAllRead'])
        ->name('notifications.markAllRead');
});

require __DIR__ . '/auth.php';
