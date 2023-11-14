@php
$dataAccordion = $dataAccordion ?? "open";
@endphp
<div id="{{ $id }}" data-accordion="{{ $dataAccordion }}" collapseAll="true">
    {{ $slot }}
</div>