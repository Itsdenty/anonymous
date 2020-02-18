<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;
use App\Classes\ContactQueryBuilder;


trait ContactTrait {

  public function scopeAdvancedFilter($query) {

    // return $query->paginate();
    return $this->process($query, request()->all())
          ->orderBy(
            request('order_column', 'sub_date'),
            request('order_direction', 'desc')
          )
          ->paginate(request('limit', 10));
  }

  public function process($query, $data) {

    // return $query;
    $v = validator()->make($data, [
      'order_column' => 'sometimes|required|in:'.$this->orderableColumns(),
      'order_direction' => 'sometimes|required|in:asc,desc',
      'limit' => 'sometimes|required|integer|min:1',

      //advanced filter
      'filter_match' => 'sometimes|required|in:and,or',
      'f' => 'sometimes|required|array',
      'f.*.column' => 'required|in:'.$this->whiteListColumns(),
      'f.*.operator' => 'required_with:f.*.column|in:'.$this->allowedOperators(),
      'f.*.query_1' => 'required',
      'f.*.query_2' => 'required_if:f.*.operator,between,not_between'
    ]);

    if($v->fails()) {
      return dd($v->messages()->all());

      throw new ValidationException($v);
    }

    return (new ContactQueryBuilder)->apply($query, $data);
  }

  protected function whiteListColumns() {
    return implode(',', $this->allowedFilters);
  }

  protected function orderableColumns() {
    return implode(',', $this->orderable);
  }

  protected function allowedOperators() {
    return implode(',', [
      'equal_to',
      'not_equal_to',
      'less_than',
      'greater_than',
      'between',
      'not_between',
      'contains',
      'starts_with',
      'ends_with',
      'in_the_past',
      'in_the_next',
      'in_the_period',
      'less_than_count',
      'greater_than_count',
      'not_equal_to_count',
      'has_tag',
      'has_not_tag',
      'in_segment',
      'not_in_segment',
      'email_openers',
      'non_email_openers',
      'link_clickers',
      'non_link_clickers',
      'in_custom',
      'not_in_custom'
    ]);
  }
}