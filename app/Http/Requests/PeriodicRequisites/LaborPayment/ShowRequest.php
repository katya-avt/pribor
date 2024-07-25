<?php

namespace App\Http\Requests\PeriodicRequisites\LaborPayment;

use App\Http\Filters\FilterConstants;
use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $period = $this->input('period');
        $quarter = $this->input('quarter');
        $month = $this->input('month');
        $date = $this->input('date');
        $from_date = $this->input('from_date');
        $to_date = $this->input('to_date');

        $periods = implode(',', array_keys(FilterConstants::PERIODS));

        return [
            'period' => $period ? [
                "in:$periods"
            ] : [],
            'quarter' => $quarter ? [
                'date'
            ] : [],
            'month' => $month ? [
                'date'
            ] : [],
            'date' => $date ? [
                'date'
            ] : [],
            'from_date' => $from_date || $to_date ? [
                'required_with:to_date', 'date'
            ] : [],
            'to_date' => $from_date || $to_date ? [
                'required_with:from_date', 'date'
            ] : []
        ];
    }
}
