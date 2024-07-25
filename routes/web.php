<?php

use App\Http\Controllers\Admin\MarkedForDeletion\Item\IndexController as AdminMarkedForDeletionItemIndexController;
use App\Http\Controllers\Admin\MarkedForDeletion\Item\RestoreController as AdminMarkedForDeletionItemRestoreController;
use App\Http\Controllers\Admin\MarkedForDeletion\User\IndexController as AdminMarkedForDeletionUserIndexController;
use App\Http\Controllers\Admin\MarkedForDeletion\User\RestoreController as AdminMarkedForDeletionUserRestoreController;
use App\Http\Controllers\Admin\User\CreateController as AdminUserCreateController;
use App\Http\Controllers\Admin\User\DestroyController as AdminUserDestroyController;
use App\Http\Controllers\Admin\User\EditController as AdminUserEditController;
use App\Http\Controllers\Admin\User\IndexController as AdminUserIndexController;
use App\Http\Controllers\Admin\User\StoreController as AdminUserStoreController;
use App\Http\Controllers\Admin\User\UpdateController as AdminUserUpdateController;
use App\Http\Controllers\ChoiceModals\Orders\Customer\IndexController as ChoiceModalsOrdersCustomerIndexController;
use App\Http\Controllers\ChoiceModals\Range\Group\IndexController as ChoiceModalsRangeGroupIndexController;
use App\Http\Controllers\ChoiceModals\Range\Item\CurrentSpecificationsAndRoute\Cover\IndexController as ChoiceModalsRangeItemCurrentSpecificationsAndRouteCoverIndexController;
use App\Http\Controllers\ChoiceModals\Range\Item\CurrentSpecificationsAndRoute\Route\IndexController as ChoiceModalsRangeItemCurrentSpecificationsAndRouteRouteIndexController;
use App\Http\Controllers\ChoiceModals\Range\Item\CurrentSpecificationsAndRoute\Specification\IndexController as ChoiceModalsRangeItemCurrentSpecificationsAndRouteSpecificationIndexController;
use App\Http\Controllers\ChoiceModals\Range\Item\IndexController as ChoiceModalsRangeItemIndexController;
use App\Http\Controllers\ChoiceModals\Range\ItemType\IndexController as ChoiceModalsRangeItemTypeIndexController;
use App\Http\Controllers\ChoiceModals\Range\MainWarehouse\IndexController as ChoiceModalsRangeMainWarehouseIndexController;
use App\Http\Controllers\ChoiceModals\Range\ManufactureType\IndexController as ChoiceModalsRangeManufactureTypeIndexController;
use App\Http\Controllers\ChoiceModals\Range\ManufacturingProcess\Cover\IndexController as ChoiceModalsRangeManufacturingProcessCoverIndexController;
use App\Http\Controllers\ChoiceModals\Range\ManufacturingProcess\Route\IndexController as ChoiceModalsRangeManufacturingProcessRouteIndexController;
use App\Http\Controllers\ChoiceModals\Range\ManufacturingProcess\Specification\IndexController as ChoiceModalsRangeManufacturingProcessSpecificationIndexController;
use App\Http\Controllers\ChoiceModals\Range\Operation\IndexController as ChoiceModalsRangeOperationIndexController;
use App\Http\Controllers\ChoiceModals\Range\Point\IndexController as ChoiceModalsRangePointIndexController;
use App\Http\Controllers\ChoiceModals\Range\Unit\IndexController as ChoiceModalsRangeUnitIndexController;
use App\Http\Controllers\ItemAvailabilityAndConsumption\Availability\EditController as AvailabilityEditController;
use App\Http\Controllers\ItemAvailabilityAndConsumption\Availability\IndexController as AvailabilityIndexController;
use App\Http\Controllers\ItemAvailabilityAndConsumption\Availability\UpdateController as AvailabilityUpdateController;
use App\Http\Controllers\ItemAvailabilityAndConsumption\Consumption\IndexController as ConsumptionIndexController;
use App\Http\Controllers\ItemAvailabilityAndConsumption\Consumption\ShowController as ConsumptionShowController;
use App\Http\Controllers\ItemAvailabilityAndConsumption\OrderPoint\IndexController as OrderPointIndexController;
use App\Http\Controllers\Orders\Order\CompleteProductionController as OrdersOrderCompleteProductionController;
use App\Http\Controllers\Orders\Order\CreateController as OrdersOrderCreateController;
use App\Http\Controllers\Orders\Order\DestroyController as OrdersOrderDestroyController;
use App\Http\Controllers\Orders\Order\EditController as OrdersOrderEditController;
use App\Http\Controllers\Orders\Order\IndexController as OrdersOrderIndexController;
use App\Http\Controllers\Orders\Order\OrderItem\CreateController as OrdersOrderItemCreateControllersOrderItem;
use App\Http\Controllers\Orders\Order\OrderItem\DestroyController as OrdersOrderItemDestroyController;
use App\Http\Controllers\Orders\Order\OrderItem\EditController as OrdersOrderItemEditControllersOrderItem;
use App\Http\Controllers\Orders\Order\OrderItem\IndexController as OrdersOrderItemIndexController;
use App\Http\Controllers\Orders\Order\OrderItem\StoreController as OrdersOrderItemStoreControllersOrderItem;
use App\Http\Controllers\Orders\Order\OrderItem\UpdateController as OrdersOrderItemUpdateControllersOrderItem;
use App\Http\Controllers\Orders\Order\PutIntoProductionController as OrdersOrderPutIntoProductionController;
use App\Http\Controllers\Orders\Order\SendOnShipmentController as OrdersOrderSendOnShipmentController;
use App\Http\Controllers\Orders\Order\ShipController as OrdersOrderShipController;
use App\Http\Controllers\Orders\Order\StoreController as OrdersOrderStoreController;
use App\Http\Controllers\Orders\Order\UpdateController as OrdersOrderUpdateController;
use App\Http\Controllers\Orders\ShippedOrderStatistics\IndexController as ShippedOrderStatisticsIndexController;
use App\Http\Controllers\PeriodicRequisites\LaborPayment\EditController as PeriodicRequisitesLaborPaymentEditController;
use App\Http\Controllers\PeriodicRequisites\LaborPayment\IndexController as PeriodicRequisitesLaborPaymentIndexController;
use App\Http\Controllers\PeriodicRequisites\LaborPayment\ShowController as PeriodicRequisitesLaborPaymentShowController;
use App\Http\Controllers\PeriodicRequisites\LaborPayment\UpdateController as PeriodicRequisitesLaborPaymentUpdateController;
use App\Http\Controllers\PeriodicRequisites\PurchasePrice\EditController as PeriodicRequisitesPurchasePriceEditController;
use App\Http\Controllers\PeriodicRequisites\PurchasePrice\IndexController as PeriodicRequisitesPurchasePriceIndexController;
use App\Http\Controllers\PeriodicRequisites\PurchasePrice\ShowController as PeriodicRequisitesPurchasePriceShowController;
use App\Http\Controllers\PeriodicRequisites\PurchasePrice\UpdateController as PeriodicRequisitesPurchasePriceUpdateController;
use App\Http\Controllers\Range\Item\CreateController as ItemCreateController;
use App\Http\Controllers\Range\Item\CurrentSpecificationsAndRoute\EditController as ItemCurrentSpecificationsAndRouteEditController;
use App\Http\Controllers\Range\Item\CurrentSpecificationsAndRoute\UpdateController as ItemCurrentSpecificationsAndRouteUpdateController;
use App\Http\Controllers\Range\Item\Destroy\ConfirmComponentReplacementController;
use App\Http\Controllers\Range\Item\Destroy\ConfirmCoverReplacementController;
use App\Http\Controllers\Range\Item\Destroy\DestroyController as ItemDestroyController;
use App\Http\Controllers\Range\Item\Destroy\ReplacementComponentStoreController;
use App\Http\Controllers\Range\Item\Destroy\ReplacementCoverStoreController;
use App\Http\Controllers\Range\Item\EditController as ItemEditController;
use App\Http\Controllers\Range\Item\IndexController as ItemIndexController;
use App\Http\Controllers\Range\Item\ManufacturingProcess\Cover\CreateController as ItemCoverCreateController;
use App\Http\Controllers\Range\Item\ManufacturingProcess\Cover\DestroyController as ItemCoverDestroyController;
use App\Http\Controllers\Range\Item\ManufacturingProcess\Cover\IndexController as ItemCoverIndexController;
use App\Http\Controllers\Range\Item\ManufacturingProcess\Cover\StoreController as ItemCoverStoreController;
use App\Http\Controllers\Range\Item\ManufacturingProcess\Route\CreateController as ItemRouteCreateController;
use App\Http\Controllers\Range\Item\ManufacturingProcess\Route\DestroyController as ItemRouteDestroyController;
use App\Http\Controllers\Range\Item\ManufacturingProcess\Route\IndexController as ItemRouteIndexController;
use App\Http\Controllers\Range\Item\ManufacturingProcess\Route\StoreController as ItemRouteStoreController;
use App\Http\Controllers\Range\Item\ManufacturingProcess\Specification\CreateController as ItemSpecificationCreateController;
use App\Http\Controllers\Range\Item\ManufacturingProcess\Specification\DestroyController as ItemSpecificationDestroyController;
use App\Http\Controllers\Range\Item\ManufacturingProcess\Specification\IndexController as ItemSpecificationIndexController;
use App\Http\Controllers\Range\Item\ManufacturingProcess\Specification\StoreController as ItemSpecificationStoreController;
use App\Http\Controllers\Range\Item\ShowController as ItemShowController;
use App\Http\Controllers\Range\Item\FormD5\IndexController as ItemFormD5IndexController;
use App\Http\Controllers\Range\Item\FormD5\ExportController as ItemFormD5ExportController;
use App\Http\Controllers\Range\ManufacturingProcess\Cover\ExportController as CoverExportController;
use App\Http\Controllers\Range\ManufacturingProcess\Route\ExportController as RouteExportController;
use App\Http\Controllers\Range\ManufacturingProcess\Specification\ExportController as SpecificationExportController;
use App\Http\Controllers\Range\Item\StoreController as ItemStoreController;
use App\Http\Controllers\Range\Item\UpdateController as ItemUpdateController;
use App\Http\Controllers\Range\ManufacturingProcess\Cover\CoverItem\CreateController as CoverItemCreateController;
use App\Http\Controllers\Range\ManufacturingProcess\Cover\CoverItem\DestroyController as CoverItemDestroyController;
use App\Http\Controllers\Range\ManufacturingProcess\Cover\CoverItem\EditController as CoverItemEditController;
use App\Http\Controllers\Range\ManufacturingProcess\Cover\CoverItem\StoreController as CoverItemStoreController;
use App\Http\Controllers\Range\ManufacturingProcess\Cover\CoverItem\UpdateController as CoverItemUpdateController;
use App\Http\Controllers\Range\ManufacturingProcess\Cover\CreateController as CoverCreateController;
use App\Http\Controllers\Range\ManufacturingProcess\Cover\DestroyController as CoverDestroyController;
use App\Http\Controllers\Range\ManufacturingProcess\Cover\EditController as CoverEditController;
use App\Http\Controllers\Range\ManufacturingProcess\Cover\IndexController as CoverIndexController;
use App\Http\Controllers\Range\ManufacturingProcess\Cover\ShowController as CoverShowController;
use App\Http\Controllers\Range\ManufacturingProcess\Cover\StoreController as CoverStoreController;
use App\Http\Controllers\Range\ManufacturingProcess\Cover\UpdateController as CoverUpdateController;
use App\Http\Controllers\Range\ManufacturingProcess\Route\CreateController as RouteCreateController;
use App\Http\Controllers\Range\ManufacturingProcess\Route\DestroyController as RouteDestroyController;
use App\Http\Controllers\Range\ManufacturingProcess\Route\EditController as RouteEditController;
use App\Http\Controllers\Range\ManufacturingProcess\Route\IndexController as RouteIndexController;
use App\Http\Controllers\Range\ManufacturingProcess\Route\RoutePoint\CreateController as RoutePointCreateController;
use App\Http\Controllers\Range\ManufacturingProcess\Route\RoutePoint\DestroyController as RoutePointDestroyController;
use App\Http\Controllers\Range\ManufacturingProcess\Route\RoutePoint\EditController as RoutePointEditController;
use App\Http\Controllers\Range\ManufacturingProcess\Route\RoutePoint\RearrangeController as RoutePointRearrangeController;
use App\Http\Controllers\Range\ManufacturingProcess\Route\RoutePoint\RearrangeUpdateController as RoutePointRearrangeStoreController;
use App\Http\Controllers\Range\ManufacturingProcess\Route\RoutePoint\StoreController as RoutePointStoreController;
use App\Http\Controllers\Range\ManufacturingProcess\Route\RoutePoint\UpdateController as RoutePointUpdateController;
use App\Http\Controllers\Range\ManufacturingProcess\Route\ShowController as RouteShowController;
use App\Http\Controllers\Range\ManufacturingProcess\Route\StoreController as RouteStoreController;
use App\Http\Controllers\Range\ManufacturingProcess\Route\UpdateController as RouteUpdateController;
use App\Http\Controllers\Range\ManufacturingProcess\Specification\CreateController as SpecificationCreateController;
use App\Http\Controllers\Range\ManufacturingProcess\Specification\DestroyController as SpecificationDestroyController;
use App\Http\Controllers\Range\ManufacturingProcess\Specification\EditController as SpecificationEditController;
use App\Http\Controllers\Range\ManufacturingProcess\Specification\IndexController as SpecificationIndexController;
use App\Http\Controllers\Range\ManufacturingProcess\Specification\ShowController as SpecificationShowController;
use App\Http\Controllers\Range\ManufacturingProcess\Specification\SpecificationItem\CreateController as SpecificationItemCreateController;
use App\Http\Controllers\Range\ManufacturingProcess\Specification\SpecificationItem\DestroyController as SpecificationItemDestroyController;
use App\Http\Controllers\Range\ManufacturingProcess\Specification\SpecificationItem\EditController as SpecificationItemEditController;
use App\Http\Controllers\Range\ManufacturingProcess\Specification\SpecificationItem\StoreController as SpecificationItemStoreController;
use App\Http\Controllers\Range\ManufacturingProcess\Specification\SpecificationItem\UpdateController as SpecificationItemUpdateController;
use App\Http\Controllers\Range\ManufacturingProcess\Specification\StoreController as SpecificationStoreController;
use App\Http\Controllers\Range\ManufacturingProcess\Specification\UpdateController as SpecificationUpdateController;
use App\Http\Controllers\Range\PurchasedItems\EditController as RangePurchasedItemsEditController;
use App\Http\Controllers\Range\PurchasedItems\IndexController as RangePurchasedItemsIndexController;
use App\Http\Controllers\Range\PurchasedItems\UpdateController as RangePurchasedItemsUpdateController;
use App\Models\Users\Permission;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::prefix('items')->name('items.')->group(function () {
        Route::middleware('can:' . Permission::ITEMS_MANAGE)->group(function () {
            Route::get('/create', ItemCreateController::class)->name('create');
            Route::post('/', ItemStoreController::class)->name('store');
            Route::get('/{item}/edit', ItemEditController::class)->name('edit')->middleware(['item', 'itemNotAddedToOrder']);
            Route::get('/{item}/current-specifications-and-route/edit', ItemCurrentSpecificationsAndRouteEditController::class)->name('current-specifications-and-route.edit')->middleware('item');
            Route::patch('/{item}', ItemUpdateController::class)->name('update')->middleware('item');
            Route::patch('/{item}/current-specifications-and-route', ItemCurrentSpecificationsAndRouteUpdateController::class)->name('current-specifications-and-route.update')->middleware('item');
            Route::delete('/{item}', ItemDestroyController::class)->name('destroy')->middleware('item');

            Route::get('/{item}/confirm-component-replacement', ConfirmComponentReplacementController::class)->name('confirm-component-replacement')->middleware('item');
            Route::post('/{item}/confirm-component-replacement', ReplacementComponentStoreController::class)->name('component-replacement.store')->middleware('item');

            Route::get('/{item}/confirm-cover-replacement', ConfirmCoverReplacementController::class)->name('confirm-cover-replacement')->middleware('item');
            Route::post('/{item}/confirm-cover-replacement', ReplacementCoverStoreController::class)->name('cover-replacement.store')->middleware('item');
        });

        Route::middleware('can:' . Permission::ITEMS_VIEW)->group(function () {
            Route::get('/', ItemIndexController::class)->name('index');
            Route::get('/{item}', ItemShowController::class)->name('show')->middleware('item');
            Route::get('/{item}/form-d5', ItemFormD5IndexController::class)->name('form-d5.index')->middleware('item');
            Route::get('/{item}/form-d5/export', ItemFormD5ExportController::class)->name('form-d5.export')->middleware('item');
        });

        Route::prefix('/{item}/specifications')->name('specifications.')->middleware(['item', 'specificationsOnlyForProprietaryItems'])->group(function () {
            Route::middleware('can:' . Permission::SPECIFICATIONS_VIEW)->group(function () {
                Route::get('/', ItemSpecificationIndexController::class)->name('index');
            });
            Route::middleware('can:' . Permission::SPECIFICATIONS_MANAGE)->group(function () {
                Route::get('/create', ItemSpecificationCreateController::class)->name('create');
                Route::post('/', ItemSpecificationStoreController::class)->name('store');
                Route::delete('/{specification}', ItemSpecificationDestroyController::class)->name('destroy');
            });
        });

        Route::prefix('/{item}/covers')->name('covers.')->middleware(['item', 'coversOnlyForNotCovers'])->group(function () {
            Route::middleware('can:' . Permission::COVERS_VIEW)->group(function () {
                Route::get('/', ItemCoverIndexController::class)->name('index');
            });
            Route::middleware('can:' . Permission::COVERS_MANAGE)->group(function () {
                Route::get('/create', ItemCoverCreateController::class)->name('create');
                Route::post('/', ItemCoverStoreController::class)->name('store');
                Route::delete('/{cover}', ItemCoverDestroyController::class)->name('destroy');
            });
        });

        Route::prefix('/{item}/routes')->name('routes.')->middleware('item')->group(function () {
            Route::middleware('can:' . Permission::ROUTES_VIEW)->group(function () {
                Route::get('/', ItemRouteIndexController::class)->name('index');
            });
            Route::middleware('can:' . Permission::ROUTES_MANAGE)->group(function () {
                Route::get('/create', ItemRouteCreateController::class)->name('create');
                Route::post('/', ItemRouteStoreController::class)->name('store');
                Route::delete('/{route}', ItemRouteDestroyController::class)->name('destroy');
            });
        });
    });

    Route::prefix('specifications')->name('specifications.')->group(function () {
        Route::middleware('can:' . Permission::SPECIFICATIONS_MANAGE)->group(function () {
            Route::get('/create', SpecificationCreateController::class)->name('create');
            Route::post('/', SpecificationStoreController::class)->name('store');
            Route::get('/{specification}/edit', SpecificationEditController::class)->name('edit')
                ->middleware(['specification', 'specificationNotAddedToOrder']);
            Route::patch('/{specification}', SpecificationUpdateController::class)->name('update')
                ->middleware(['specification', 'specificationNotAddedToOrder']);
            Route::delete('/{specification}', SpecificationDestroyController::class)->name('destroy')
                ->middleware(['specification', 'specificationNotAddedToOrder']);

            Route::prefix('/{specification}')->name('specification-items.')->middleware(['specification', 'detailSpecification', 'specificationNotAddedToOrder'])->group(function () {
                Route::get('/create', SpecificationItemCreateController::class)->name('create');
                Route::post('/', SpecificationItemStoreController::class)->name('store');
            });

            Route::prefix('/{specification}')->name('specification-items.')->middleware(['specification', 'specificationItem', 'specificationNotAddedToOrder'])->group(function () {
                Route::get('/{specificationItem}/edit', SpecificationItemEditController::class)->name('edit');
                Route::patch('/{specificationItem}', SpecificationItemUpdateController::class)->name('update');
                Route::delete('/{specificationItem}', SpecificationItemDestroyController::class)->name('destroy');
            });
        });

        Route::middleware('can:' . Permission::SPECIFICATIONS_VIEW)->group(function () {
            Route::get('/', SpecificationIndexController::class)->name('index');
            Route::get('/{specification}', SpecificationShowController::class)->name('show')->middleware('specification');
            Route::get('/{specification}/export', SpecificationExportController::class)->name('export')->middleware('specification');
        });
    });

    Route::prefix('covers')->name('covers.')->group(function () {
        Route::middleware('can:' . Permission::COVERS_MANAGE)->group(function () {
            Route::get('/create', CoverCreateController::class)->name('create');
            Route::post('/', CoverStoreController::class)->name('store');
            Route::get('/{cover}/edit', CoverEditController::class)->name('edit')
                ->middleware(['cover', 'coverNotAddedToOrder']);
            Route::patch('/{cover}', CoverUpdateController::class)->name('update')
                ->middleware(['cover', 'coverNotAddedToOrder']);
            Route::delete('/{cover}', CoverDestroyController::class)->name('destroy')
                ->middleware(['cover', 'coverNotAddedToOrder']);

            Route::prefix('/{cover}')->name('cover-items.')->middleware(['cover', 'coverNotAddedToOrder'])->group(function () {
                Route::get('/create', CoverItemCreateController::class)->name('create');
                Route::post('/', CoverItemStoreController::class)->name('store');
            });

            Route::prefix('/{cover}')->name('cover-items.')->middleware(['cover', 'coverItem', 'coverNotAddedToOrder'])->group(function () {
                Route::get('/{coverItem}/edit', CoverItemEditController::class)->name('edit');
                Route::patch('/{coverItem}', CoverItemUpdateController::class)->name('update');
                Route::delete('/{coverItem}', CoverItemDestroyController::class)->name('destroy');
            });
        });

        Route::middleware('can:' . Permission::COVERS_VIEW)->group(function () {
            Route::get('/', CoverIndexController::class)->name('index');
            Route::get('/{cover}', CoverShowController::class)->name('show')->middleware('cover');
            Route::get('/{cover}/export', CoverExportController::class)->name('export')->middleware('cover');
        });
    });

    Route::prefix('routes')->name('routes.')->group(function () {
        Route::middleware('can:' . Permission::ROUTES_MANAGE)->group(function () {
            Route::get('/create', RouteCreateController::class)->name('create');
            Route::post('/', RouteStoreController::class)->name('store');
            Route::get('/{route}/edit', RouteEditController::class)->name('edit')
                ->middleware(['route', 'routeNotAddedToOrder']);
            Route::patch('/{route}', RouteUpdateController::class)->name('update')
                ->middleware(['route', 'routeNotAddedToOrder']);
            Route::delete('/{route}', RouteDestroyController::class)->name('destroy')
                ->middleware(['route', 'routeNotAddedToOrder']);

            Route::prefix('/{route}')->name('route-points.')->middleware(['route', 'routeNotAddedToOrder'])->group(function () {
                Route::get('/create', RoutePointCreateController::class)->name('create');
                Route::post('/', RoutePointStoreController::class)->name('store');

                Route::get('/rearrange', RoutePointRearrangeController::class)->name('rearrange');
                Route::patch('/rearrange', RoutePointRearrangeStoreController::class)->name('rearrange.update');
            });

            Route::prefix('/{route}')->name('route-points.')->middleware(['route', 'pointNumber', 'routeNotAddedToOrder'])->group(function () {
                Route::get('/{pointNumber}/edit', RoutePointEditController::class)->name('edit');
                Route::patch('/{pointNumber}', RoutePointUpdateController::class)->name('update');
                Route::delete('/{pointNumber}', RoutePointDestroyController::class)->name('destroy');
            });
        });

        Route::middleware('can:' . Permission::ROUTES_VIEW)->group(function () {
            Route::get('/', RouteIndexController::class)->name('index');
            Route::get('/{route}', RouteShowController::class)->name('show')->middleware('route');
            Route::get('/{route}/export', RouteExportController::class)->name('export')->middleware('route');
        });
    });

    Route::prefix('purchased-items')->name('purchased-items.')->middleware('can:' . Permission::ITEMS_MANAGE)->group(function () {
        Route::get('/', RangePurchasedItemsIndexController::class)->name('index');
        Route::get('/{item}/edit', RangePurchasedItemsEditController::class)->name('edit')->middleware('purchasedItem');
        Route::patch('/{item}', RangePurchasedItemsUpdateController::class)->name('update')->middleware('purchasedItem');
    });

    Route::name('item-availability-and-consumption.')->group(function () {
        Route::prefix('/availability')->name('availability.')->group(function () {
            Route::middleware('can:' . Permission::ITEMS_IN_STOCK_VIEW)->group(function () {
                Route::get('/', AvailabilityIndexController::class)->name('index');
            });
            Route::middleware('can:' . Permission::ITEMS_IN_STOCK_MANAGE)->group(function () {
                Route::get('/{item}/edit', AvailabilityEditController::class)->name('edit')->middleware('item');
                Route::patch('/{item}', AvailabilityUpdateController::class)->name('update')->middleware('item');
            });
        });

        Route::prefix('/consumption')->name('consumption.')->group(function () {
            Route::middleware('can:' . Permission::ITEMS_CONSUMPTION_VIEW)->group(function () {
                Route::get('/', ConsumptionIndexController::class)->name('index');
                Route::get('/{order}', ConsumptionShowController::class)->name('show')->middleware('inProductionOrder');
            });
        });

        Route::prefix('/order-point')->name('order-point.')->group(function () {
            Route::middleware('can:' . Permission::ORDER_POINT_VIEW)->group(function () {
                Route::get('/', OrderPointIndexController::class)->name('index')->middleware('can:' . Permission::ORDER_POINT_VIEW);
            });
        });
    });


    Route::get('/item-choice', ChoiceModalsRangeItemIndexController::class)->name('item-choice');
    Route::get('/group-choice', ChoiceModalsRangeGroupIndexController::class)->name('group-choice');
    Route::get('/item-type-choice', ChoiceModalsRangeItemTypeIndexController::class)->name('item-type-choice');
    Route::get('/main-warehouse-choice', ChoiceModalsRangeMainWarehouseIndexController::class)->name('main-warehouse-choice');
    Route::get('/manufacture-type-choice', ChoiceModalsRangeManufactureTypeIndexController::class)->name('manufacture-type-choice');
    Route::get('/cover-choice', ChoiceModalsRangeManufacturingProcessCoverIndexController::class)->name('cover-choice');
    Route::get('/specification-choice', ChoiceModalsRangeManufacturingProcessSpecificationIndexController::class)->name('specification-choice');
    Route::get('/route-choice', ChoiceModalsRangeManufacturingProcessRouteIndexController::class)->name('route-choice');
    Route::get('/operation-choice', ChoiceModalsRangeOperationIndexController::class)->name('operation-choice');
    Route::get('/point-choice', ChoiceModalsRangePointIndexController::class)->name('point-choice');
    Route::get('/unit-choice', ChoiceModalsRangeUnitIndexController::class)->name('unit-choice');
    Route::get('/customer-choice', ChoiceModalsOrdersCustomerIndexController::class)->name('customer-choice');

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::middleware('can:' . Permission::ORDERS_MANAGE)->group(function () {
            Route::get('/create', OrdersOrderCreateController::class)->name('create');
            Route::post('/', OrdersOrderStoreController::class)->name('store');
        });

        Route::prefix('/{order}')->name('order-items.')->middleware('order')->group(function () {
            Route::get('/', OrdersOrderItemIndexController::class)->name('index');
        });

        Route::get('/status/{orderStatus}', OrdersOrderIndexController::class)->name('index')->middleware('orderStatus');
    });

    Route::prefix('orders/{order}')->name('orders.')->middleware('order')->group(function () {
        Route::patch('/put-into-production', OrdersOrderPutIntoProductionController::class)->name('put-into-production')->middleware(['pendingOrder', 'can:' . Permission::PUT_ORDER_INTO_PRODUCTION]);
        Route::patch('/complete-production', OrdersOrderCompleteProductionController::class)->name('complete-production')->middleware(['inProductionOrder', 'can:' . Permission::COMPLETE_ORDER_PRODUCTION]);
        Route::patch('/send-on-shipment', OrdersOrderSendOnShipmentController::class)->name('send-on-shipment')->middleware(['completeProductionOrder', 'can:' . Permission::SEND_ORDER_ON_SHIPMENT]);
        Route::patch('/ship', OrdersOrderShipController::class)->name('ship')->middleware(['onShipmentOrder', 'can:' . Permission::SHIP_ORDER]);
    });

    Route::prefix('orders')->name('orders.')->middleware('pendingOrder')->group(function () {
        Route::middleware('can:' . Permission::ORDERS_MANAGE)->group(function () {
            Route::get('/{order}/edit', OrdersOrderEditController::class)->name('edit')->middleware('order');
            Route::patch('/{order}', OrdersOrderUpdateController::class)->name('update')->middleware('order');
            Route::delete('/{order}', OrdersOrderDestroyController::class)->name('destroy')->middleware('order');
        });
        Route::prefix('/{order}')->name('order-items.')->middleware('order')->group(function () {
            Route::middleware('can:' . Permission::ORDERS_MANAGE)->group(function () {
                Route::get('/create', OrdersOrderItemCreateControllersOrderItem::class)->name('create');
                Route::post('/', OrdersOrderItemStoreControllersOrderItem::class)->name('store');
            });
        });

        Route::prefix('/{order}')->name('order-items.')->middleware(['order', 'orderItem', 'can:' . Permission::ORDERS_MANAGE])->group(function () {
            Route::get('/{orderItem}/edit', OrdersOrderItemEditControllersOrderItem::class)->name('edit');
            Route::patch('/{orderItem}', OrdersOrderItemUpdateControllersOrderItem::class)->name('update');
            Route::delete('/{orderItem}', OrdersOrderItemDestroyController::class)->name('destroy');
        });
    });

    Route::middleware(['can:' . Permission::ITEMS_MANAGE, 'item'])->group(function () {
        Route::get('/current-item-specification-choice/{item}', ChoiceModalsRangeItemCurrentSpecificationsAndRouteSpecificationIndexController::class)->name('current-item-specification-choice');
        Route::get('/current-item-cover-choice/{item}', ChoiceModalsRangeItemCurrentSpecificationsAndRouteCoverIndexController::class)->name('current-item-cover-choice');
        Route::get('/current-item-route-choice/{item}', ChoiceModalsRangeItemCurrentSpecificationsAndRouteRouteIndexController::class)->name('current-item-route-choice');
    });

    Route::name('periodic-requisites.')->group(function () {
        Route::prefix('/purchase-price')->name('purchase-price.')->group(function () {
            Route::get('/', PeriodicRequisitesPurchasePriceIndexController::class)->name('index')->middleware('can:' . Permission::PERIODIC_REQUISITES_VIEW);
            Route::get('/{item}', PeriodicRequisitesPurchasePriceShowController::class)->name('show')->middleware(['can:' . Permission::PERIODIC_REQUISITES_VIEW, 'purchasedItem']);
            Route::get('/{item}/edit', PeriodicRequisitesPurchasePriceEditController::class)->name('edit')->middleware(['can:' . Permission::PERIODIC_REQUISITES_MANAGE, 'purchasedItem']);
            Route::patch('/{item}', PeriodicRequisitesPurchasePriceUpdateController::class)->name('update')->middleware(['can:' . Permission::PERIODIC_REQUISITES_MANAGE, 'purchasedItem']);
        });
        Route::prefix('/labor-payment')->name('labor-payment.')->group(function () {
            Route::get('/', PeriodicRequisitesLaborPaymentIndexController::class)->name('index')->middleware('can:' . Permission::PERIODIC_REQUISITES_VIEW);
            Route::get('/{point}', PeriodicRequisitesLaborPaymentShowController::class)->name('show')->middleware(['can:' . Permission::PERIODIC_REQUISITES_VIEW, 'point']);
            Route::get('/{point}/edit', PeriodicRequisitesLaborPaymentEditController::class)->name('edit')->middleware(['can:' . Permission::PERIODIC_REQUISITES_MANAGE, 'point']);
            Route::patch('/{point}', PeriodicRequisitesLaborPaymentUpdateController::class)->name('update')->middleware(['can:' . Permission::PERIODIC_REQUISITES_MANAGE, 'point']);
        });
    });


    Route::prefix('/shipped-order-statistics')->name('shipped-order-statistics.')->middleware('can:' . Permission::SHIPPED_ORDERS_VIEW)->group(function () {
        Route::get('/', ShippedOrderStatisticsIndexController::class)->name('index');
    });

    Route::prefix('/admin')->name('admin.')->middleware('admin')->group(function () {

        Route::prefix('/users')->name('users.')->group(function () {
            Route::get('/', AdminUserIndexController::class)->name('index');
            Route::get('/create', AdminUserCreateController::class)->name('create');
            Route::post('/', AdminUserStoreController::class)->name('store');
            Route::get('/{user}/edit', AdminUserEditController::class)->name('edit')->middleware('user');
            Route::patch('/{user}', AdminUserUpdateController::class)->name('update')->middleware('user');
            Route::delete('/{user}', AdminUserDestroyController::class)->name('destroy')->middleware('user');
        });

        Route::prefix('/marked-for-deletion')->name('marked-for-deletion.')->group(function () {
            Route::prefix('/users')->name('users.')->group(function () {
                Route::get('/', AdminMarkedForDeletionUserIndexController::class)->name('index');
                Route::patch('/{user}', AdminMarkedForDeletionUserRestoreController::class)->name('restore')
                    ->withTrashed()->middleware('markedForDeletionUser');
            });
            Route::prefix('/items')->name('items.')->group(function () {
                Route::get('/', AdminMarkedForDeletionItemIndexController::class)->name('index');
                Route::patch('/{item}', AdminMarkedForDeletionItemRestoreController::class)->name('restore')
                    ->withTrashed()->middleware('markedForDeletionItem');
            });
        });
    });
});
