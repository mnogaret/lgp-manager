<div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <h5 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Adhésions</h5>
    <ul>
        @foreach ($adhesions as $adhesion)
            <x-adhesion-li :adhesion="$adhesion" />
        @endforeach
    </ul>
</div>
