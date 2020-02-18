<?php

namespace App\Classes;

class ContactQueryBuilder {

  public function apply($query, $data) {

    if(isset($data['f'])) {
      foreach($data['f'] as $filter) {
        $filter['match'] = isset($filter['filter_match']) ? $filter['filter_match'] : 'and';
        $this->makeFilter($query, $filter);
      }
    }
    return $query;
  }

  protected function makeFilter($query, $filter) {

    if(strpos($filter['column'], '.') !== false) {
    //nested column
      list($relation, $filter['column']) = explode('.', $filter['column']);

      $filter['match'] = 'and';

      if($filter['column'] == 'count') {
        $this->{camel_case($filter['operator'])}($filter, $query, $relation);
      }
      else {
        $query->whereHas($relation, function($q) use ($filter) {
          $this->{camel_case($filter['operator'])}($filter, $q);
        });
      }
    }
    else {
      $this->{camel_case($filter['operator'])}($filter, $query); //the 'equalTo' method
    }
  }

  public function equalTo($filter, $query) {
    return $query->where($filter['column'], '=', $filter['query_1'], $filter['match']);
  }
  public function notEqualTo($filter, $query) {
    return $query->where($filter['column'], '<>', $filter['query_1'], $filter['match']);
  }
  public function lessThan($filter, $query) {
    return $query->where($filter['column'], '<', $filter['query_1'], $filter['match']);
  }
  public function greaterThan($filter, $query) {
    return $query->where($filter['column'], '>', $filter['query_1'], $filter['match']);
  }
  public function between($filter, $query) {
    return $query->whereBetween($filter['column'], [
        $filter['query_1'], $filter['query_2']
    ], $filter['match']);
  }
  public function notBetween($filter, $query) {
    return $query->whereNotBetween($filter['column'], [
        $filter['query_1'], $filter['query_2']
    ], $filter['match']);
  }
  public function contains($filter, $query) {
    return $query->where($filter['column'], 'like', '%' . $filter['query_1'] . '%', $filter['match']);
  }
  public function startsWith($filter, $query) {
    return $query->where($filter['column'], 'like', $filter['query_1'] . '%', $filter['match']);
  }
  public function endsWith($filter, $query) {
    return $query->where($filter['column'], 'like', '%' . $filter['query_1'], $filter['match']);
  }

  public function inThePast($filter, $query)
    {
        $end = now()->endOfDay();

        $begin = now();

        switch ($filter['query_2']) {
            case 'hours':
                $begin->subHours($filter['query_1']);
                break;
            case 'days':
                $begin->subDays($filter['query_1'])->startOfDay();
                break;

            case 'months':
                $begin->subMonths($filter['query_1'])->startOfDay();
                break;

            case 'years':
                $begin->subYears($filter['query_1'])->startOfDay();
                break;

            default:
                $begin->subDays($filter['query_1'])->startOfDay();
                break;
        }

        return $query->whereBetween($filter['column'], [$begin, $end], $filter['match']);
    }

    public function inTheNext($filter, $query)
    {
        $begin = now()->startOfDay();

        $end = now();

        switch ($filter['query_2']) {
            case 'hours':
                $end->addHours($filter['query_1']);
                break;
            case 'days':
                $end->addDays($filter['query_1'])->endOfDay();
                break;

            case 'months':
                $end->addMonths($filter['query_1'])->endOfDay();
                break;

            case 'years':
                $end->addYears($filter['query_1'])->endOfDay();
                break;

            default:
                $end->addDays($filter['query_1'])->endOfDay();
                break;
        }

        return $query->whereBetween($filter['column'], [$begin, $end], $filter['match']);
    }

  public function inThePeriod($filter, $query) {
    $begin = now();
    $end = now();

    switch ($filter['query_1']) {
      case 'today':
        $begin->startOfDay();
        $end->endOfDay();
        break;
      case 'yesterday':
        $begin->subDay(1)->startOfDay();
        $end->subDay(1)->endOfDay();
        break;
      case 'tomorrow':
        $begin->addDay(1)->startOfDay();
        $end->addDay(1)->endOfDay();
        break;
      case 'last_month':
        $begin->subMonth(1)->startOfMonth();
        $end->subMonth(1)->endOfMonth();
        break;
      case 'this_month':
        $begin->startOfMonth();
        $end->endOfMonth();
        break;
      case 'next_month':
        $begin->addMonth(1)->startOfMonth();
        $end->addMonth(1)->endOfMonth();
        break;
      case 'last_year':
        $begin->subYear(1)->startOfYear();
        $end->subYear(1)->endOfYear();
        break;
      case 'this_year':
        $begin->startOfYear();
        $end->endOfYear();
        break;
      case 'next_year':
        $begin->addYear(1)->startOfYear();
        $end->addYear(1)->endOfYear();
        break;
      
      default:
        break;
    }

    return $query->whereBetween($filter['column'], [$begin, $end], $filter['match']);
  }

  public function equalToCount($filter, $query, $relation) {
    return $query->has($relation, '=', $filter['query_1']);
  }
  public function notEqualToCount($filter, $query, $relation) {
    return $query->has($relation, '<>', $filter['query_1']);
  }
  public function lessThanCount($filter, $query, $relation) {
    return $query->has($relation, '<', $filter['query_1']);
  }
  public function greaterThanCount($filter, $query, $relation) {
    return $query->has($relation, '>', $filter['query_1']);
  }

  public function hasTag($filter, $query){
    return $query->whereHas('tags', function($q) use ($filter){
      $q->where('tags.id', $filter['query_1']);
    });
  }

  public function hasNotTag($filter, $query){
    return $query->whereDoesntHave('tags', function($q) use ($filter){
      $q->where('tags.id', $filter['query_1']);
    });
  }

  public function inSegment($filter, $query){
    return $query->whereHas('segments', function($q) use ($filter){
      $q->where('segments.id', $filter['query_1']);
    });
  }

  public function notInSegment($filter, $query){
    return $query->whereDoesntHave('segments', function($q) use ($filter){
      $q->where('segments.id', $filter['query_1']);
    });
  }

  public function emailOpeners($filter, $query){
    if($filter["query_1"] == "all_campaigns")
    {
      if(isset($filter["start"]) && isset($filter["end"]))
      {
        return $query->whereHas('campaigns', function($q) use ($filter){
          $q->where('opened', true)->whereBetween('campaign_contact.updated_at', [$filter["start"], $filter["end"]]);
        });
      }
      return $query->whereHas('campaigns', function($q) use ($filter){
        $q->where('opened', true);
      });
    }
    else if($filter["query_1"] == "specific_campaign")
    {
      if(isset($filter["campaign_id"]))
      {
        if(isset($filter["start"]) && isset($filter["end"]))
        {
          return $query->whereHas('campaigns', function($q) use ($filter){
            $q->where('campaigns.id', $filter['campaign_id'])->where('opened', true)->whereBetween('campaign_contact.updated_at', [$filter["start"], $filter["end"]]);
          });
        }
        return $query->whereHas('campaigns', function($q) use ($filter){
          $q->where('campaigns.id', $filter['campaign_id'])->where('opened', true);
        });
      }
    }
    return $query->whereHas('campaigns', function($q) use ($filter){
      $q->where('opened', true);
    });
  }

  public function nonEmailOpeners($filter, $query){
    if($filter["query_1"] == "all_campaigns")
    {
      if(isset($filter["start"]) && isset($filter["end"]))
      {
        return $query->whereDoesntHave('campaigns', function($q) use ($filter){
          $q->where('opened', true);
        })->whereHas('campaigns', function($q) use ($filter){
          $q->whereBetween('campaign_contact.updated_at', [$filter["start"], $filter["end"]]);
        });
      }
      return $query->whereDoesntHave('campaigns', function($q) use ($filter){
        $q->where('opened', true);
      });
    }
    else if($filter["query_1"] == "specific_campaign")
    {
      if(isset($filter["campaign_id"]))
      {
        if(isset($filter["start"]) && isset($filter["end"]))
        {
          return $query->whereHas('campaigns', function($q) use ($filter){
            $q->where('campaigns.id', $filter['campaign_id'])->whereBetween('campaign_contact.updated_at', [$filter["start"], $filter["end"]])->where('opened', false);
          });
        }
        return $query->whereHas('campaigns', function($q) use ($filter){
          $q->where('campaigns.id', $filter['campaign_id'])->where('opened', false);;
        });
      }
    }
    return $query->whereHas('campaigns', function($q) use ($filter){
      $q->where('opened', true);
    });
  }

  public function linkClickers($filter, $query){
    if($filter["query_1"] == "all_campaigns")
    {
      if($filter["link_option"] == "all_links")
      {
        if(isset($filter["start"]) && isset($filter["end"]))
        {
            return $query->whereHas('links', function($q) use ($filter){
              $q->whereHas('contacts', function($q) use($filter){
                $q->whereBetween('contact_link.updated_at', [$filter["start"], $filter["end"]]);
              });
            });
        }
        return $query->whereHas('links', function($q) use ($filter){
          $q->whereHas('contacts', function($q) use($filter){
            $q;
          });
        });
      }
      else
      {
        if(isset($filter["link_id"]))
        {
          if(isset($filter["start"]) && isset($filter["end"]))
          {
            return $query->whereHas('links', function($q) use ($filter){
              $q->where('id', $filter["link_id"])->whereHas('contacts', function($q) use($filter){
                $q->whereBetween('contact_link.updated_at', [$filter["start"], $filter["end"]]);;
              });
            });
          }
          return $query->whereHas('links', function($q) use ($filter){
            $q->where('id', $filter["link_id"])->whereHas('contacts', function($q) use($filter){
              $q;
            });
          });
        }
      }
    }
    else if($filter["query_1"] == "specific_campaign")
    {
      if(isset($filter["campaign_id"]))
      {
        if($filter["link_option"] == "all_links")
        {
          if(isset($filter["start"]) && isset($filter["end"]))
          {
            return $query->whereHas('links', function($q) use ($filter){
              $q->where('campaign_id', $filter["campaign_id"])->whereHas('contacts', function($q) use($filter){
                $q->whereBetween('contact_link.updated_at', [$filter["start"], $filter["end"]]);
              });
            });
          }
          return $query->whereHas('links', function($q) use ($filter){
            $q->where('campaign_id', $filter["campaign_id"])->whereHas('contacts', function($q) use($filter){
              $q;
            });
          });
        }
        else
        {
          if(isset($filter["link_id"]))
          {
            if(isset($filter["start"]) && isset($filter["end"]))
            {
              return $query->whereHas('links', function($q) use ($filter){
                  $q->where('campaign_id', $filter["campaign_id"])->where('id', $filter["link_id"])->whereHas('contacts', function($q) use($filter){
                    $q->whereBetween('contact_link.updated_at', [$filter["start"], $filter["end"]]);
                  });
              });
            }
            return $query->whereHas('links', function($q) use ($filter){
              $q->where('campaign_id', $filter["campaign_id"])->where('id', $filter["link_id"])->whereHas('contacts', function($q) use($filter){
                $q;
              });
          });
          }
        }
      }
    }
  }

  public function nonLinkClickers($filter, $query){
    if($filter["query_1"] == "all_campaigns")
    {
      if($filter["link_option"] == "all_links")
      {
        if(isset($filter["start"]) && isset($filter["end"]))
        {
            return $query->whereHas('campaigns', function($q) use ($filter){
              $q->where('sent', true)->whereHas('links', function($q) use ($filter){
                $q->whereDoesntHave('contacts', function($q) use($filter){
                  $q->whereBetween('contact_link.updated_at', [$filter["start"], $filter["end"]]);
                });
              });
            });
        }
        return $query->whereHas('campaigns', function($q) use ($filter){
          $q->where('sent', true)->whereHas('links', function($q) use ($filter){
            $q->whereDoesntHave('contacts', function($q) use($filter){
              $q;
            });
          });
        });
          
      }
      else
      {
        if(isset($filter["link_id"]))
        {
          if(isset($filter["start"]) && isset($filter["end"]))
          {
            return $query->whereHas('campaigns', function($q) use ($filter){
              $q->where('sent', true)->whereHas('links', function($q) use ($filter){
                $q->where('id', $filter["link_id"])->whereDoesntHave('contacts', function($q) use($filter){
                  $q->whereBetween('contact_link.updated_at', [$filter["start"], $filter["end"]]);
                });
              });
            });
          }
          return $query->whereHas('campaigns', function($q) use ($filter){
            $q->where('sent', true)->whereHas('links', function($q) use ($filter){
              $q->where('id', $filter["link_id"])->whereDoesntHave('contacts', function($q) use($filter){
                $q;
              });
            });
          });
        }
      }
    }
    else if($filter["query_1"] == "specific_campaign")
    {
      if(isset($filter["campaign_id"]))
      {
        if($filter["link_option"] == "all_links")
        {
          if(isset($filter["start"]) && isset($filter["end"]))
          {
            return $query->whereHas('campaigns', function($q) use ($filter){
              $q->where('sent', true)->whereHas('links', function($q) use ($filter){
                $q->where('campaign_id', $filter["campaign_id"])->whereDoesntHave('contacts', function($q) use($filter){
                  $q->whereBetween('contact_link.updated_at', [$filter["start"], $filter["end"]]);
                });
              });
            });
          }
          return $query->whereHas('campaigns', function($q) use ($filter){
            $q->where('sent', true)->whereHas('links', function($q) use ($filter){
              $q->where('campaign_id', $filter["campaign_id"])->whereDoesntHave('contacts', function($q) use($filter){
                $q;
              });
            });
          });
        }
        else
        {
          if(isset($filter["link_id"]))
          {
            if(isset($filter["start"]) && isset($filter["end"]))
            {
              return $query->whereHas('campaigns', function($q) use ($filter){
                $q->where('sent', true)->whereHas('links', function($q) use ($filter){
                  $q->where('campaign_id', $filter["campaign_id"])->where('id', $filter["link_id"])->whereDoesntHave('contacts', function($q) use($filter){
                    $q->whereBetween('contact_link.updated_at', [$filter["start"], $filter["end"]]);
                  });
                });
              });
            }
            return $query->whereHas('campaigns', function($q) use ($filter){
              $q->where('sent', true)->whereHas('links', function($q) use ($filter){
                $q->where('campaign_id', $filter["campaign_id"])->where('id', $filter["link_id"])->whereDoesntHave('contacts', function($q) use($filter){
                  $q;
                });
              });
            });
          }
        }
      }
    }
  }

  public function inCustom($filter, $query){
    return $query->whereHas('contactAttributes', function($q) use ($filter){
      $q->where('contact_attributes.id', $filter['query_1'])->where('value', $filter['query_2']);
    });
  }

  public function notInCustom($filter, $query){
    return $query->whereDoesntHave('contactAttributes', function($q) use ($filter){
      $q->where('contact_attributes.id', $filter['query_1'])->where('value', $filter['query_2']);
    });
  }
}