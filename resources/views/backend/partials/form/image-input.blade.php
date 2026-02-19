@php
    $inputId = $id ?? $name;
    $defaultFile = $value ?? null;
    $removeName = $removeName ?? ($name . '_remove');
@endphp

<div class="form-field-wrapper">
    <div class="form-group">
        <label for="{{ $inputId }}">{{ $label }}</label>
        <input
            id="{{ $inputId }}"
            type="file"
            name="{{ $name }}"
            class="dropify @error($name) is-invalid @enderror"
            @if(!empty($accept)) accept="{{ $accept }}" @endif
            @if(!empty($defaultFile)) data-default-file="{{ $defaultFile }}" @endif
            @if(!empty($height)) data-height="{{ $height }}" @endif
            data-remove-flag-name="{{ $removeName }}"
        >
        <input type="hidden" name="{{ $removeName }}" value="0" data-remove-flag>
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
