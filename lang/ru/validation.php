<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Атрибут :attribute должен быть принят.',
    'accepted_if' => 'Атрибут :attribute должен быть принят, если атрибут :other равен :value.',
    'active_url' => 'Атрибут :attribute не является действительным URL.',
    'after' => 'Атрибут :attribute должен быть датой, превышающей :date.',
    'after_or_equal' => 'Атрибут :attribute должен быть датой, превышающей или совпадающей с :date.',
    'alpha' => 'Атрибут :attribute должен содержать только буквы.',
    'alpha_dash' => 'Атрибут :attribute должен содержать только буквы, цифры, тире и знаки подчеркивания.',
    'alpha_num' => 'Атрибут :attribute должен содержать только буквы и цифры.',
    'array' => 'Атрибут :attribute должен быть массивом.',
    'ascii' => 'Атрибут :attribute должен содержать только однобайтовые цифро-буквенные символы.',
    'before' => 'Атрибут :attribute должен быть датой, предшествующей :date.',
    'before_or_equal' => 'Атрибут :attribute должен быть датой, предшествующей или совпадающей с :date.',
    'between' => [
        'array' => 'Атрибут :attribute должен содержать от :min до :max элементов.',
        'file' => 'Атрибут :attribute должен иметь размер от :min до :max килобайт.',
        'numeric' => 'Атрибут :attribute должен иметь значения от :min до :max.',
        'string' => 'Атрибут :attribute должен содержать от :min до :max символов.',
    ],
    'boolean' => 'Атрибут :attribute должен принимать логическое значение.',
    'confirmed' => 'Атрибут :attribute не совпадает с подтверждением.',
    'current_password' => 'Пароль неверен.',
    'date' => 'Атрибут :attribute не является корректной датой.',
    'date_equals' => 'Атрибут :attribute должен быть датой, совпадающей с :date.',
    'date_format' => 'Атрибут :attribute не соответствует формату :format.',
    'decimal' => 'Атрибут :attribute должен содержать :decimal десятичных разрядов.',
    'declined' => 'Атрибут :attribute должен быть отклонен.',
    'declined_if' => 'Атрибут :attribute должен быть отклонен, если атрибут :other равен :value.',
    'different' => 'Атрибут :attribute и :other должны быть различными.',
    'digits' => 'Атрибут :attribute должен содержать :digits символов.',
    'digits_between' => 'Атрибут :attribute должен содержать от :min до :max символов.',
    'dimensions' => 'Атрибут :attribute имеет недопустимые размеры изображения.',
    'distinct' => 'Атрибут :attribute содержит повторяющееся значения.',
    'doesnt_end_with' => 'Атрибут :attribute не может заканчиваться одним из следующих значений: :values.',
    'doesnt_start_with' => 'Атрибут :attribute не может начинаться с одного из следующих значений: :values.',
    'email' => 'Атрибут :attribute должен быть действительным адресом электронной почты.',
    'ends_with' => 'Атрибут :attribute должен заканчиваться одним из следующих значений: :values.',
    'enum' => 'Выбранное значение атрибута :attribute некорректно.',
    'exists' => 'Выбранное значение атрибута :attribute некорректно.',
    'file' => 'Атрибут :attribute должен быть файлом.',
    'filled' => 'Атрибут :attribute должен быть заполнен.',
    'gt' => [
        'array' => 'Атрибут :attribute должен содержать более, чем :value элементов.',
        'file' => 'Атрибут :attribute должен иметь размер, превышающий :value килобайт.',
        'numeric' => 'Атрибут :attribute должен иметь значение, превышающее :value.',
        'string' => 'Атрибут :attribute должен содержать более, чем :value символов.',
    ],
    'gte' => [
        'array' => 'Атрибут :attribute должен содержать :value элементов или более.',
        'file' => 'Атрибут :attribute должен иметь размер, превышающий или равный :value килобайт.',
        'numeric' => 'Атрибут :attribute должен иметь значение, превышающее или равное :value.',
        'string' => 'Атрибут :attribute должен содержать :value символов или более.',
    ],
    'image' => 'Атрибут :attribute должен быть изображением.',
    'in' => 'Выбранное значение атрибута :attribute некорректно.',
    'in_array' => 'Атрибут :attribute не указан в :other.',
    'integer' => 'Атрибут :attribute должен быть целым числом.',
    'ip' => 'Атрибут :attribute должен быть действительным IP-адресом.',
    'ipv4' => 'Атрибут :attribute должен быть действительным IPv4-адресом.',
    'ipv6' => 'Атрибут :attribute должен быть действительным IPv6-адресом.',
    'json' => 'Атрибут :attribute должен быть корректной JSON строкой.',
    'lowercase' => 'Атрибут :attribute должен быть в нижнем регистре.',
    'lt' => [
        'array' => 'Атрибут :attribute должен содержать менее, чем :value элементов.',
        'file' => 'Атрибут :attribute должен иметь размер, не превышающий :value килобайт.',
        'numeric' => 'Атрибут :attribute должен иметь значение, не превышающее :value.',
        'string' => 'Атрибут :attribute должен содержать менее, чем :value символов.',
    ],
    'lte' => [
        'array' => 'Атрибут :attribute должен содержать :value элементов или менее.',
        'file' => 'Атрибут :attribute должен иметь размер, не превышающий или равный :value килобайт.',
        'numeric' => 'Атрибут :attribute должен иметь значение, не превышающее или равное :value.',
        'string' => 'Атрибут :attribute должен содержать :value символов или менее.',
    ],
    'mac_address' => 'Атрибут :attribute должен быть корректным MAC-адресом.',
    'max' => [
        'array' => 'Атрибут :attribute не должен содержать более, чем :max элементов.',
        'file' => 'Атрибут :attribute не должен иметь размер, превышающий :max килобайт.',
        'numeric' => 'Атрибут :attribute не должен иметь значение, превышающее :max.',
        'string' => 'Атрибут :attribute не должен содержать более, чем :max символов.',
    ],
    'max_digits' => 'Атрибут :attribute не должен содержать более, чем :max цифр.',
    'mimes' => 'Атрибут :attribute должен быть файлом одного из следующих типов: :values.',
    'mimetypes' => 'Атрибут :attribute должен быть файлом одного из следующих типов: :values.',
    'min' => [
        'array' => 'Атрибут :attribute должен содержать не менее :min элементов.',
        'file' => 'Атрибут :attribute должен иметь размер не менее :min килобайт.',
        'numeric' => 'Атрибут :attribute должен иметь значение не менее :min.',
        'string' => 'Атрибут :attribute должен содержать не менее :min символов.',
    ],
    'min_digits' => 'Атрибут :attribute должен содержать не менее :min цифр.',
    'missing' => 'Атрибут :attribute должен отсутствовать.',
    'missing_if' => 'Атрибут :attribute должен отсутствовать, если атрибут :other равен :value.',
    'missing_unless' => 'Атрибут :attribute должен отсутствовать, если атрибут :other не равен :value.',
    'missing_with' => 'Атрибут :attribute должен отсутствовать, если атрибут :values указан.',
    'missing_with_all' => 'Атрибут :attribute должен отсутствовать, если атрибут :values указаны.',
    'multiple_of' => 'Атрибут :attribute должен быть кратным :value.',
    'not_in' => 'Выбранное значение атрибута :attribute некорректно.',
    'not_regex' => 'Атрибут :attribute не соответствует формату.',
    'numeric' => 'Атрибут :attribute должен быть числом.',
    'password' => [
        'letters' => 'Пароль должен содержать хотя бы одну букву.',
        'mixed' => 'Пароль должен содержать хотя бы одну прописную и одну строчную буквы.',
        'numbers' => 'Пароль должен содержать хотя бы одну цифру.',
        'symbols' => 'Пароль должен содержать хотя бы один символ.',
        'uncompromised' => 'Указанный пароль был обнаружен в утечке данных. Пожалуйста, выберите другое значение пароля.',
    ],
    'present' => 'Атрибут :attribute должен быть задан.',
    'prohibited' => 'Атрибут :attribute не должен быть задан.',
    'prohibited_if' => 'Атрибут :attribute не должен быть задан, если атрибут :other равен :value.',
    'prohibited_unless' => 'Атрибут :attribute не должен быть задан, если атрибут :other не имеет одно из следующих значений: :values.',
    'prohibits' => 'Атрибут :attribute не позволяет :other быть заданым.',
    'regex' => 'Атрибут :attribute не соответствует формату.',
    'required' => 'Атрибут :attribute обязателен для заполнения.',
    'required_array_keys' => 'Атрибут :attribute должен иметь следующие ключи: :values.',
    'required_if' => 'Атрибут :attribute обязателен для заполнения, если атрибут :other равен :value.',
    'required_if_accepted' => 'Атрибут :attribute обязателен для заполнения, если атрибут :other принят.',
    'required_unless' => 'Атрибут :attribute field обязателен для заполнения, если атрибут :other не имеет одно из следующих значений: :values.',
    'required_with' => 'Атрибут :attribute обязателен для заполнения, если атрибут :values задан.',
    'required_with_all' => 'Атрибут :attribute обязателен для заполнения, если атрибуты :values заданы.',
    'required_without' => 'Атрибут :attribute обязателен для заполнения, если атрибут :values не задан.',
    'required_without_all' => 'Атрибут :attribute обязателен для заполнения, если ни один из атрибутов :values не задан.',
    'same' => 'Атрибуты :attribute и :other должны совпадать.',
    'size' => [
        'array' => 'Атрибут :attribute должен содержать :size элементов.',
        'file' => 'Атрибут :attribute должен иметь размер :size килобайт.',
        'numeric' => 'Атрибут :attribute должен иметь значение :size.',
        'string' => 'Атрибут :attribute должен содержать :size символов.',
    ],
    'starts_with' => 'Атрибут :attribute должен начинаться с одного из следующих значений: :values.',
    'string' => 'Атрибут :attribute должен быть строкой.',
    'timezone' => 'Атрибут :attribute должен быть действительным часовым поясом.',
    'unique' => 'Такое значение атрибута :attribute уже существует.',
    'uploaded' => 'Загрузка :attribute не удалась.',
    'uppercase' => 'Атрибут :attribute должен быть в верхнем регистре.',
    'url' => 'Атрибут :attribute должен быть действительным URL-адресом.',
    'ulid' => 'Атрибут :attribute должен быть корректным ULID.',
    'uuid' => 'Атрибут :attribute должен быть корректным UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        //app/Http/Requests/Range/Item/CurrentSpecificationsAndRoute/UpdateRequest.php
        'specification_number' => 'Номер спецификации',
        'cover_number' => 'Номер покрытия',
        'route_number' => 'Номер маршрута',

        //app/Http/Requests/Range/Item/Destroy/ComponentStoreRequest.php
        //app/Http/Requests/Range/Item/Destroy/CoverStoreRequest.php
        'drawing' => 'Чертеж',
        'factor' => 'Коэффициент',

        //app/Http/Requests/Range/Item/ManufacturingProcess/Cover/StoreRequest.php
        //app/Http/Requests/Range/Item/ManufacturingProcess/Route/StoreRequest.php
        //app/Http/Requests/Range/Item/ManufacturingProcess/Specification/StoreRequest.php
        'number' => 'Номер',

        //app/Http/Requests/Range/Item/IndexRequest.php
        'search' => 'Поиск',
        'item_type_id' => 'Тип номенклатуры',
        'main_warehouse_code' => 'Основной склад',
        'group_id' => 'Группа',
        'manufacture_type_id' => 'Тип изготовления',

        //app/Http/Requests/Range/Item/StoreRequest.php
        //app/Http/Requests/Range/Item/UpdateRequest.php
        'item.drawing' => 'Чертеж',
        'item.unit_code' => 'Основная ед.изм.',
        'detail.detail_size' => 'Размер детали',
        'detail.billet_size' => 'Размер заготовки',
        'item.name' => 'Наименование',
        'item.group_id' => 'Группа',
        'item.main_warehouse_code' => 'Основной склад',
        'item.item_type_id' => 'Тип номенклатуры',
        'item.manufacture_type_id' => 'Тип изготовления',
        'purchased.purchase_price' => 'Цена покупки',
        'purchased.purchase_lot' => 'Партия закупки',
        'purchased.order_point' => 'Точка заказа',
        'purchased.unit_factor' => 'Коэфф. ед. изм.',
        'purchased.unit_code' => 'Ед/изм. закупки',

        //app/Http/Requests/Range/ManufacturingProcess/Cover/CoverItem/StoreRequest.php
        //app/Http/Requests/Range/ManufacturingProcess/Cover/CoverItem/UpdateRequest.php
        'area' => 'Площадь',
        'consumption' => 'Потребление',

        //app/Http/Requests/Range/ManufacturingProcess/Route/RoutePoint/RearrangeUpdateRequest.php
        'order.*' => 'Порядок точки маршрута',

        //app/Http/Requests/Range/ManufacturingProcess/Route/RoutePoint/StoreRequest.php
        //app/Http/Requests/Range/ManufacturingProcess/Route/RoutePoint/UpdateRequest.php
        'point_code' => 'Точка маршрута',
        'operation_code' => 'Операция',
        'unit_time' => 'Т_Шт',
        'working_time' => 'Т_Пов',
        'lead_time' => 'Т_ПЗ',
        'rate_code' => 'Ставка',

        //app/Http/Requests/Range/ManufacturingProcess/Specification/SpecificationItem/StoreRequest.php
        //app/Http/Requests/Range/ManufacturingProcess/Specification/SpecificationItem/UpdateRequest.php
        'cnt' => 'Кол-во',

        //app/Http/Requests/Range/PurchasedItems/UpdateRequest.php
        'purchase_lot' => 'Партия закупки',
        'order_point' => 'Точка заказа',

        //app/Http/Requests/Orders/Order/OrderItem/StoreRequest.php
        //app/Http/Requests/Orders/Order/OrderItem/UpdateRequest.php
        'item_id' => 'Чертеж',
        'per_unit_price' => 'Цена за единицу',

        //app/Http/Requests/Orders/Order/IndexRequest.php
        'customer_inn' => 'Заказчик',
        'sort_by' => 'Сортировать по',
        'sort_direction' => 'Порядок',

        //app/Http/Requests/Orders/Order/StoreRequest.php
        //app/Http/Requests/Orders/Order/UpdateRequest.php
        'code' => 'Код',
        'name' => 'Наименование',
        'closing_date' => 'Дата закрытия',
        'note' => 'Примечание',

        //app/Http/Requests/Orders/ShippedOrderStatistics/IndexRequest.php
        'period' => 'Период',
        'quarter' => 'Квартал',
        'month' => 'Месяц',
        'date' => 'День',
        'from_date' => 'Интервал с',
        'to_date' => 'Интервал по',

        //app/Http/Requests/PeriodicRequisites/LaborPayment/UpdateRequest.php
        'base_payment' => 'Ставка',

        //app/Http/Requests/PeriodicRequisites/PurchasePrice/UpdateRequest.php
        'purchase_price' => 'Цена покупки',

        //app/Http/Requests/Admin/User/StoreRequest.php
        //app/Http/Requests/Admin/User/UpdateRequest.php
        'email' => 'Email-адрес',
        'password' => 'Пароль',
        'role_id' => 'Роль',
    ],

];
