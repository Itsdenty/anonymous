<?php
/** 
  * Semantic UI
  * Includes previous and next buttons
  * @example $pages->links('pagination-advanced', ['paginator' => $pages])
  * @example @include('pagination-advanced', ['paginator' => $pages])
  *
  * @link https://semantic-ui.com/collections/menu.html#inverted Inverted styles
  * @see <div class="ui pagination inverted blue menu"> Inverted blue menu
**/
?>
@if ($paginator->lastPage() > 1)
    <div class="ui pagination menu">
        <a href="{{ $paginator->previousPageUrl() }}" class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }} item">
            Previous
        </a>
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <a href="{{ $paginator->url($i) }}" class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }} item">
                {{ $i }}
            </a>
        @endfor
        <a href="{{ $paginator->nextPageUrl() }}" class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }} item">
            Next
        </a>
    </div>
@endif