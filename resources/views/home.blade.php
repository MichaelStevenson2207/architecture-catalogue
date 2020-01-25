@extends('layouts.base')

@section('content')
<div class="">
    <h1 class="govuk-heading-xl">Architecture Catalogue</h1>
    <p class="govuk-body">
      This architecture catalogue contains all of the solution building blocks (physical components) used by the NICS. This list is used to populate the NICS Architecture Portal which provides a publically accessible reference for architects designing solutions for the NICS.
    </p>
    @if (auth()->user()->isContributor())
        <a class="govuk-button" data-module="govuk-button" href="{{ route('entry.create') }}">
            Add a new catalogue entry
        </a>
    @endif
</div>
@endsection
