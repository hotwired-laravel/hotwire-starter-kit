@props(['id' => null, 'label' => null, 'description' => null])

<label class="fieldset-label">
    <input {{ $attributes->merge(['id' => $id, 'type' => 'checkbox', 'class' => 'checkbox checkbox-sm']) }} />
    {{ $label }}
</label>
