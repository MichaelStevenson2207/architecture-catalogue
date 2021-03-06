<!--
  *
  * List row component using GOV.UK styling and pattern
  *
-->
<div class="govuk-summary-list__row">
    <dt class="govuk-summary-list__key">
        {{ $attribute }}
    </dt>
    <dd class="govuk-summary-list__value">
        {{ $value }}
    </dd>
    <dd class="govuk-summary-list__actions">
        {{ $action ?? '' }}
    </dd>
</div>
