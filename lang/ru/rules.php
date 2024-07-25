<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Messages Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages in custom validation for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'quantity_for_unit_items_must_be_integer' => 'Кол-во для штучных изделий должно быть целым.',

    //app/Rules/Orders/Order/OrderItem/OrderItemSpecificationValidate.php
    'proprietary_items_must_have_specification' => 'Заполните порядок изготовления изделия перед добавлением в заказ. Собственные изделия должны иметь спецификацию.',
    'tolling_items_must_have_route' => 'Заполните порядок изготовления изделия перед добавлением в заказ. Давальческие изделия должны иметь маршрут.',

    'unique_rule_for_order_item_table' => 'Данное изделие уже есть в этом заказе.',

    'manufacturing_process_is_mandatory_to_be_filled_in' => 'Выберете порядок изготовления изделия.',
    'only_proprietary_items_may_have_specification' => 'Только собственные изделия могут иметь спецификацию.',

    //app/Rules/Range/Item/CurrentSpecificationsAndRoute/OptionMustBeSelectedFromListDependingOnValue.php
    'option_must_be_selected_from_list' => 'Выберете значение из списка.',
    'list_is_empty' => 'Список пуст.',

    'route_is_required_if_specification_given' => 'Укажите номер маршрута.',

    'factor_for_unit_items_must_be_integer' => 'Коэффициент для штучных изделий должен быть целым.',
    'item_to_be_replaced_and_replacement_item_must_be_different' => 'Заменяющее изделие должно отличаться от заменяемого.',
    'item_to_be_replaced_group_and_replacement_item_group_must_be_the_same' => 'Группы заменяемого и заменяющего изделий должны совпадать.',

    'detail_specification_cannot_contain_more_than_one_element' => 'Спецификация детали не может содержать более одного элемента.',

    'selected_manufacturing_process_must_not_be_empty' => 'Выбранный порядок изготовления не должен быть пустым. Заполните его перед добавлением изделию.',
    'unique_rule_for_item_manufacturing_process_table' => 'Данное изделие уже имеет указанную спецификацию.',

    'factor_equal_one_when_units_match' => 'Если основная ед.изм. и ед.изм. закупки совпадают, то коэффициент ед.изм. равен 1.',
    'factor_equal_thousand_when_units_are_kg_and_ton' => 'Если основная ед.изм. - кг, а ед.изм. закупки - тонны, то коэффициент ед.изм. равен 1000.',

    'base_unit_validation_by_group' => 'Выберете коррректную для данной группы изделий единицу измерения.',
    'details_only_field' => 'Это поле заполняется только для деталей.',
    'item_type_validation_by_group' => 'Выберете коррректный для данной группы изделий тип номенклатуры.',
    'purchased_items_only_field' => 'Это поле заполняется только для покупных изделий.',
    'purchased_item_unit_validation_by_group' => 'Выберете коррректную для данной группы изделий единицу измерения закупки.',
    'value_for_unit_purchase_lot_must_be_integer' => 'Это значение должно быть целым для штучных закупок.',

    'item_must_be_cover' => 'Изделие должно быть покрытием.',

    'route_point_number_must_not_exceed_current_quantity' => 'Номер точки маршрута не должен превышать их текущее кол-во.',
    'route_point_number_must_not_repeat' => 'Номер точки маршрута не должен повторяться.',

    'item_must_not_be_cover' => 'Изделие не должно быть покрытием.',

    'unique_rule_for_specification_item_table' => 'Данное изделие уже содержится в спецификации.',

    'assembly_item_specification_may_contain' => 'Спецификация сборочной единицы может содержать детали, крепеж, другие сборочные единицы, материалы из подгруппы \"Разные\", а также кабельные изделия.',
    'detail_specification_may_contain' => 'Спецификация детали может содержать металлы или пластмассы.',
    'galvanic_cover_specification_may_contain' => 'Спецификация гальванического покрытия может содержать металлы или химикаты.',
    'selected_specification_must_not_contain_the_item_for_which_it_is_selected' => 'Выбранная спецификация не должна содержать изделие, для которого она выбирается.',
    'specification_must_not_contain_the_item_for_which_it_is_filled_in' => 'Спецификация не должна содержать изделие, для которого она заполняется.',

];
