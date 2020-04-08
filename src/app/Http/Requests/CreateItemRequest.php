<?php

namespace Restart\ContentManager\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateItemRequest extends FormRequest
{
    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        /*
        $start_date = $this->get('start_date');
        $start_time = $this->get('start_time');
        $end_date = $this->get('end_date');
        $end_time = $this->get('end_time');

        $this->request->remove('start_time');
        $this->request->remove('end_time');

        if ($start_date) {
            $start_time = $start_time ? $start_time : '00:00:00';
            $this->merge(['start_date' => $start_date.' '.$start_time]);
        }
        if ($end_date) {
            $end_time = $end_time ? $end_time : '23:59:59';
            $this->merge(['end_date' => $end_date.' '.$end_time]);
        }
        */

        return parent::validationData();
    }

    /**
     * 적용될 유효성 검사 규칙을 반환합니다.
     * 
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * 적용될 유효성 검사 메세지를 반환합니다.
     * 
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
