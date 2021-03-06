@extends('layouts.base')

@section('breadcrumbs')
@if (Illuminate\Support\str::startsWith(request()->query('path'), (config('app.url') . '/entries')))
    <div class="govuk-breadcrumbs">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/home">Home</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="{{ request()->query('path') }}">Entries</a>
            </li>
            <li class="govuk-breadcrumbs__list-item" aria-current="page">View entry</li>
        </ol>
    </div>
@endif
@if (Illuminate\Support\str::contains(request()->query('path'), config('app.url') . '/catalogue/search'))
    <div class="govuk-breadcrumbs">
        <ol class="govuk-breadcrumbs__list">
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/home">Home</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="/entries/search">Search</a>
            </li>
            <li class="govuk-breadcrumbs__list-item">
                <a class="govuk-breadcrumbs__link" href="{{ request()->query('path') }}">Results</a>
            </li>
            <li class="govuk-breadcrumbs__list-item" aria-current="page">View entry</li>
        </ol>
    </div>
@endif
@endsection

@section('content')
<h1 class="govuk-heading-l">
  <span class="govuk-caption-l">Catalogue entry</span>
  {{ $entry->name }} {{ $entry->version }}
</h1>
<h2 class="govuk-heading-m">Core information</h2>
<dl class="govuk-summary-list">
    @component('components.summary-list-row')
        @slot('attribute')
          Name
        @endslot
        @slot('value')
          {{ $entry->name }}
        @endslot
    @endcomponent
    @component('components.summary-list-row')
        @slot('attribute')
          Version
        @endslot
        @slot('value')
          {{ $entry->version }}
        @endslot
    @endcomponent
    @component('components.summary-list-row')
        @slot('attribute')
          Description
        @endslot
        @slot('value')
          {{ $entry->description }}
        @endslot
    @endcomponent
    @component('components.summary-list-row')
        @slot('attribute')
          Product page URL
        @endslot
        @slot('value')
          @if ($entry->href)
              <td class="govuk-table__cell">
                  <a class="govuk-link" href="{{ $entry->href }}">
                    {{ $entry->href }}
                  </a>
              </td>
          @else
              {{ $entry->href }}
          @endif
        @endslot
    @endcomponent
    @component('components.summary-list-row')
        @slot('attribute')
          Category
        @endslot
        @slot('value')
          {{ $entry->category }}
        @endslot
    @endcomponent
    @component('components.summary-list-row')
        @slot('attribute')
          Sub-category
        @endslot
        @slot('value')
          {{ $entry->sub_category }}
        @endslot
    @endcomponent
    @component('components.summary-list-row')
        @slot('attribute')
          Status
        @endslot
        @slot('value')
          <span class="{{ $labels[$entry->status] }}">{{ $entry->status }}</span>
        @endslot
    @endcomponent
    @component('components.summary-list-row')
        @slot('attribute')
          User defined tags
        @endslot
        @slot('value')
          @foreach ($entry->tags->sortBy('name', SORT_NATURAL|SORT_FLAG_CASE) as $tag)
              <span class="label label--white govuk-!-margin-right-1">{{ $tag->name }}</span>
          @endforeach
        @endslot
        @if (auth()->user()->isContributor())
            @slot('action')
              <a class="govuk-link" href="/entries/{{ $entry->id }}/tags">
                Change<span class="govuk-visually-hidden"> change tags</span>
              </a>
            @endslot
        @endif
    @endcomponent
    @component('components.summary-list-row')
        @slot('attribute')
          Last updated on
        @endslot
        @slot('value')
          {{ $entry->updated_at->format('jS F Y') }}
        @endslot
    @endcomponent
</dl>

<h2 class="govuk-heading-m">Additional information</h2>
<dl class="govuk-summary-list">
    @component('components.summary-list-row')
        @slot('attribute')
          Functionality
        @endslot
        @slot('value')
          {{ $entry->functionality }}
        @endslot
    @endcomponent
    @component('components.summary-list-row')
        @slot('attribute')
          Service levels
        @endslot
        @slot('value')
          {{ $entry->service_levels }}
        @endslot
    @endcomponent
    @component('components.summary-list-row')
        @slot('attribute')
          Interfaces
        @endslot
        @slot('value')
          {{ $entry->interfaces }}
        @endslot
    @endcomponent
    @component('components.summary-list-row')
        @slot('attribute')
          Depends on
        @endslot
        @slot('value')
          @foreach ($entry->children as $item)
              <p class="govuk-body">
                  <a class="govuk-link" href="/entries/{{ $item->parent->id}}">
                      {{ $item->parent->name }} {{ $item->parent->version }}
                  </a>
              </p>
          @endforeach
        @endslot
        @if (auth()->user()->isContributor())
            @slot('action')
              <a class="govuk-link" href="/entries/{{ $entry->id }}/links">
                Change<span class="govuk-visually-hidden"> change related entries</span>
              </a>
            @endslot
        @endif
    @endcomponent
    @component('components.summary-list-row')
        @slot('attribute')
          Used by
        @endslot
        @slot('value')
          @foreach ($entry->parents as $item)
              <p class="govuk-body">
                  <a class="govuk-link" href="/entries/{{ $item->child->id}}">
                      {{ $item->child->name }} {{ $item->child->version }}
                  </a>
              </p>
          @endforeach
        @endslot
    @endcomponent
</dl>

<hr class="govuk-section-break govuk-section-break--m">

@if (auth()->user()->isContributor())
        <a
            class="govuk-button govuk-!-margin-right-1"
            href="/entries/{{ $entry->id }}/edit?path={{ urlencode(request()->query('path')) }}">
            Edit
        </a>
        <a
            class="govuk-button govuk-button--secondary govuk-!-margin-right-1"
            href="/entries/{{ $entry->id }}/copy">
            Make a copy
        </a>
        <p class="govuk-body">
            <a class="govuk-link" href="/entries/{{ $entry->id }}/delete">Remove this entry</a>
        </p>
    </form>
@endif
@endsection
