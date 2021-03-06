@extends('layouts.base')

@section('breadcrumbs')
<div class="govuk-breadcrumbs">
    <ol class="govuk-breadcrumbs__list">
        <li class="govuk-breadcrumbs__list-item">
          <a class="govuk-breadcrumbs__link" href="/home">Home</a>
        </li>
        <li class="govuk-breadcrumbs__list-item" aria-current="page">Entries</li>
    </ol>
</div>
@endsection

@section('content')
<h1 class="govuk-heading-l filter-heading">Browse catalogue</h1>

<div class="govuk-grid-row">
    <div class="govuk-grid-column-full">
        <form method="get" action="/entries" class="filter-section">
            {{ csrf_field() }}
            <table class="govuk-table filter-filters">
                <tbody class="govuk-table__body">
                    <tr class="govuk-table__row">
                        <th class="govuk-table__header" scope="column">
                            Status
                        </th>
                        <th class="govuk-table__header" scope="column">
                            Category / sub-category
                        </th>
                        <th class="govuk-table__header govuk-visually-hidden" scope="column">
                            Filter
                        </th>
                    </tr>
                    <tr>
                        <td class="govuk-table__cell">
                            <!-- custom version of the component to include the blank entry -->
                            @component('components.select', [
                                'name' => 'status',
                                'values' => $statuses,
                                'blank' => true,
                                'blank_label' => 'any',
                                'value' => $status
                            ])
                            @endcomponent
                        </td>
                        <td class="govuk-table__cell">
                            @component('components.group-select', [
                                'name' => 'category_subcategory',
                                'values' => $categories,
                                'blank' => true,
                                'blank_label' => 'any',
                                'value' => $sub_category
                            ])
                            @endcomponent
                        </td>
                        <td class="govuk-table__cell filter-button">
                            <button data-prevent-double-click="true" class="govuk-button" data-module="govuk-button" type="submit">
                                Filter
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

@if ($entries->count() > 0)
    @include('partials.entries-table')
@else
    <div class="govuk-warning-text">
        <span class="govuk-warning-text__icon" aria-hidden="true">!</span>
        <strong class="govuk-warning-text__text">
            <span class="govuk-warning-text__assistive">Warning</span>
            There are no entries in the catalogue.
        </strong>
    </div>
@endif
@if ( auth()->user()->isContributor())
    <a class="govuk-button" data-module="govuk-button" href="/entries/create">
        Add a new catalogue entry
    </a>
@endif
@endsection
