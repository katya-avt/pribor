<body class="hold-transition sidebar-mini layout-fixed ms-3">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        Выйти
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <span href="#" class="brand-link fs-6 text-decoration-none">
            <img src="{{ asset('img/logo.png') }}" alt="logo" class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light">ПРИБОРОСТРОИТЕЛЬ</span>
        </span>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    @can(\App\Models\Users\Permission::ITEMS_VIEW)
                        <li class="nav-item">
                            <a href="{{ route('items.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    Номенклатура
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can(\App\Models\Users\Permission::SPECIFICATIONS_VIEW)
                        <li class="nav-item">
                            <a href="{{ route('specifications.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-drafting-compass"></i>
                                <p>
                                    Спецификации
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can(\App\Models\Users\Permission::COVERS_VIEW)
                        <li class="nav-item">
                            <a href="{{ route('covers.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-paint-roller"></i>
                                <p>
                                    Покрытия
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can(\App\Models\Users\Permission::ROUTES_VIEW)
                        <li class="nav-item">
                            <a href="{{ route('routes.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-route"></i>
                                <p>
                                    Маршруты
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can(\App\Models\Users\Permission::ITEMS_MANAGE)
                        <li class="nav-item">
                            <a href="{{ route('purchased-items.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-money-check-alt"></i>
                                <p>
                                    Покупные изделия
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can(\App\Models\Users\Permission::ITEMS_IN_STOCK_VIEW)
                        <li class="nav-item">
                            <a href="{{ route('item-availability-and-consumption.availability.index') }}"
                               class="nav-link">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>
                                    В наличии
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can(\App\Models\Users\Permission::PENDING_ORDERS_VIEW)
                        <li class="nav-item">
                            <a href="{{ route('orders.index', ['orderStatus' => \App\Models\Orders\OrderStatus::PENDING_URL_PARAM_NAME]) }}"
                               class="nav-link">
                                <i class="nav-icon fas fa-clock"></i>
                                <p>
                                    Отложенные заказы
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can(\App\Models\Users\Permission::IN_PRODUCTION_ORDERS_VIEW)
                        <li class="nav-item">
                            <a href="{{ route('orders.index', ['orderStatus' => \App\Models\Orders\OrderStatus::IN_PRODUCTION_URL_PARAM_NAME]) }}"
                               class="nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    Заказы в производстве
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can(\App\Models\Users\Permission::PRODUCTION_COMPLETED_ORDERS_VIEW)
                        <li class="nav-item">
                            <a href="{{ route('orders.index', ['orderStatus' => \App\Models\Orders\OrderStatus::PRODUCTION_COMPLETED_URL_PARAM_NAME]) }}"
                               class="nav-link">
                                <i class="nav-icon fas fa-check"></i>
                                <p>
                                    Готово к отгрузке
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can(\App\Models\Users\Permission::ON_SHIPMENT_ORDERS_VIEW)
                        <li class="nav-item">
                            <a href="{{ route('orders.index', ['orderStatus' => \App\Models\Orders\OrderStatus::ON_SHIPMENT_URL_PARAM_NAME]) }}"
                               class="nav-link">
                                <i class="nav-icon fas fa-shipping-fast"></i>
                                <p>
                                    На отгрузке
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can(\App\Models\Users\Permission::SHIPPED_ORDERS_VIEW)
                        <li class="nav-item">
                            <a href="{{ route('orders.index', ['orderStatus' => \App\Models\Orders\OrderStatus::SHIPPED_URL_PARAM_NAME]) }}"
                               class="nav-link">
                                <i class="nav-icon fas fa-clipboard-check"></i>
                                <p>
                                    Отгруженные заказы
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('shipped-order-statistics.index') }}"
                               class="nav-link">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>
                                    Статистика по отгруженным заказам
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can(\App\Models\Users\Permission::ITEMS_CONSUMPTION_VIEW)
                        <li class="nav-item">
                            <a href="{{ route('item-availability-and-consumption.consumption.index') }}"
                               class="nav-link">
                                <i class="nav-icon fas fa-file-contract"></i>
                                <p>
                                    К заказам
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can(\App\Models\Users\Permission::ORDER_POINT_VIEW)
                        <li class="nav-item">
                            <a href="{{ route('item-availability-and-consumption.order-point.index') }}"
                               class="nav-link">
                                <i class="nav-icon fas fa-bell"></i>
                                <p>
                                    Точка заказа
                                    <span
                                        class="badge badge-info right">{{ \App\Services\ItemAvailabilityAndConsumption\OrderPoint\Service::getReachedItsOrderPointItemsCount() }}</span>
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can(\App\Models\Users\Permission::PERIODIC_REQUISITES_VIEW)
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>
                                    Текущие значения<br>периодических реквизитов
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('periodic-requisites.purchase-price.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-dollar-sign"></i>
                                        <p>Цена покупки</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('periodic-requisites.labor-payment.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-user-cog"></i>
                                        <p>Оплата труда</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</div>
<!-- ./wrapper -->
</body>
