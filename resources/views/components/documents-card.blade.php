<div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <h5 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Documents</h5>
    @foreach ($documents as $document)
        <a href="{{ $document->url }}" target="_blank"
            class="text-white bg-blue-700 hover:bg-blue-800 inline-flex items-center focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            <svg class="w-3 h-3 mr-2 text-gray-800 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 16 20">
                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                    d="M6 1v4a1 1 0 0 1-1 1H1m14-4v16a.97.97 0 0 1-.933 1H1.933A.97.97 0 0 1 1 18V5.828a2 2 0 0 1 .586-1.414l2.828-2.828A2 2 0 0 1 5.828 1h8.239A.97.97 0 0 1 15 2Z" />
            </svg>
            {{ $document->type }}{{ $document->extra ? ' - ' . $document->extra : '' }}
        </a>
    @endforeach
</div>
