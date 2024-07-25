<?php

namespace App\Http;

use App\Http\Middleware\Custom\Admin;
use App\Http\Middleware\Custom\DefineGate;
use App\Http\Middleware\Custom\DetailSpecification;
use App\Http\Middleware\Custom\ItemSpecifications\CoversOnlyForNotCovers;
use App\Http\Middleware\Custom\ItemSpecifications\SpecificationsOnlyForProprietaryItems;
use App\Http\Middleware\Custom\NotAddedToOrder\Cover;
use App\Http\Middleware\Custom\NotAddedToOrder\Item;
use App\Http\Middleware\Custom\NotAddedToOrder\Route;
use App\Http\Middleware\Custom\NotAddedToOrder\Specification;
use App\Http\Middleware\Custom\RouteParamsValidation\CompleteProductionOrder;
use App\Http\Middleware\Custom\RouteParamsValidation\CoverItem;
use App\Http\Middleware\Custom\RouteParamsValidation\InProductionOrder;
use App\Http\Middleware\Custom\RouteParamsValidation\MarkedForDeletionItem;
use App\Http\Middleware\Custom\RouteParamsValidation\MarkedForDeletionUser;
use App\Http\Middleware\Custom\RouteParamsValidation\OnShipmentOrder;
use App\Http\Middleware\Custom\RouteParamsValidation\OrderItem;
use App\Http\Middleware\Custom\RouteParamsValidation\OrderStatus;
use App\Http\Middleware\Custom\RouteParamsValidation\PendingOrder;
use App\Http\Middleware\Custom\RouteParamsValidation\PointNumber;
use App\Http\Middleware\Custom\RouteParamsValidation\SpecificationItem;
use App\Http\Middleware\Custom\RouteParamsValidation\Item as ItemParamValidation;
use App\Http\Middleware\Custom\RouteParamsValidation\Specification as SpecificationParamValidation;
use App\Http\Middleware\Custom\RouteParamsValidation\Cover as CoverParamValidation;
use App\Http\Middleware\Custom\RouteParamsValidation\Route as RouteParamValidation;
use App\Http\Middleware\Custom\RouteParamsValidation\PurchasedItem as PurchasedItemParamValidation;
use App\Http\Middleware\Custom\RouteParamsValidation\Order as OrderParamValidation;
use App\Http\Middleware\Custom\RouteParamsValidation\Point as PointParamValidation;
use App\Http\Middleware\Custom\RouteParamsValidation\User as UserParamValidation;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

            DefineGate::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        'coversOnlyForNotCovers' => CoversOnlyForNotCovers::class,
        'specificationsOnlyForProprietaryItems' => SpecificationsOnlyForProprietaryItems::class,

        'itemNotAddedToOrder' => Item::class,
        'specificationNotAddedToOrder' => Specification::class,
        'coverNotAddedToOrder' => Cover::class,
        'routeNotAddedToOrder' => Route::class,

        'item' => ItemParamValidation::class,
        'specification' => SpecificationParamValidation::class,
        'cover' => CoverParamValidation::class,
        'route' => RouteParamValidation::class,
        'purchasedItem' => PurchasedItemParamValidation::class,
        'order' => OrderParamValidation::class,
        'point' => PointParamValidation::class,
        'specificationItem' => SpecificationItem::class,
        'coverItem' => CoverItem::class,
        'pointNumber' => PointNumber::class,
        'inProductionOrder' => InProductionOrder::class,
        'orderStatus' => OrderStatus::class,
        'pendingOrder' => PendingOrder::class,
        'orderItem' => OrderItem::class,
        'completeProductionOrder' => CompleteProductionOrder::class,
        'onShipmentOrder' => OnShipmentOrder::class,
        'markedForDeletionUser' => MarkedForDeletionUser::class,
        'markedForDeletionItem' => MarkedForDeletionItem::class,
        'user' => UserParamValidation::class,

        'admin' => Admin::class,
        'detailSpecification' => DetailSpecification::class,
    ];
}
