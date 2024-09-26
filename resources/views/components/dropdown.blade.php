<div class="mb-6">
    @if(isset($label))
        <label for="{{ $id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</label>
    @endif
    <select id="{{ $id }}" name="{{ $id }}"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" @if($submitOnChange ?? false) onchange="this.form.submit()" @endif>

        @foreach ($options as $key => $label)
            @if ($value === $key)
                <option value="{{ $key }}" selected="selected">{{ $label }}</option>
            @else
                <option value="{{ $key }}">{{ $label }}</option>
            @endif
        @endforeach

    </select>
</div>
