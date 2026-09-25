@extends('admin.layout')

@section('title','Edit Guide: ' . $guide->model)

@section('content')

<div class="bg-white p-8 rounded-xl shadow-2xl space-y-6">

<h2 class="text-3xl font-extrabold mb-6 text-gray-800">
    Edit Guide: {{ $guide->brand }} {{$guide->model }}
</h2>

<form method="POST" action="{{ route('admin.guides.update', $guide) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- MAIN GUIDE DETAILS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-gray-200 rounded-lg bg-gray-50">

        <div>
            <label class="block text-sm font-medium text-gray-700">Device</label>
            <input name="device" class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                value="{{ old('device', $guide->device) }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Category</label>
            <input name="category" class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                value="{{ old('category', $guide->category) }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Brand</label>
            <input name="brand" class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                value="{{ old('brand', $guide->brand) }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Series</label>
            <input name="series" class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                value="{{ old('series', $guide->series) }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Model</label>
            <input name="model" class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                value="{{ old('model', $guide->model) }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Issue</label>
            <input name="issue" class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                value="{{ old('issue', $guide->issue) }}">
        </div>

        <!-- YOUTUBE URL FIELD -->
        <div class="col-span-1 md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">YouTube Video (optional)</label>
            <input name="youtube_url" class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                placeholder="https://www.youtube.com/watch?v=xxxx"
                value="{{ old('youtube_url', $guide->youtube_url) }}">
            <p class="text-xs text-gray-500 mt-1">Paste a normal YouTube link. It will be embedded automatically.</p>
        </div>
    </div>

    <h3 class="font-bold text-xl mt-8 mb-2 border-b pb-1">Causes & Solutions</h3>

    <div id="resource-wrapper">
        @php $resourceIndex = 0; @endphp
        @foreach ($guide->resources as $resource)
        <div class="border p-6 mt-4 rounded-lg bg-white shadow-inner resource-block relative">
            <div class="flex justify-between items-center mb-4">
                <span class="font-bold text-gray-600 text-sm">Block #{{ $resourceIndex + 1 }}</span>
                <button type="button" class="remove-resource text-red-600 hover:text-red-800 text-sm font-semibold flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Remove
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="font-semibold text-sm text-gray-700">Cause</label>
                    <input name="resources[{{ $resourceIndex }}][cause]"
                        class="input mt-1 w-full border-gray-300 rounded-md shadow-sm"
                        value="{{ old('resources.' . $resourceIndex . '.cause',$resource->cause) }}">
                </div>

                <div>
                    <label class="font-semibold text-sm text-gray-700">Solution</label>
                    <input name="resources[{{ $resourceIndex }}][solution]"
                        class="input mt-1 w-full border-gray-300 rounded-md shadow-sm"
                        value="{{ old('resources.' . $resourceIndex . '.solution',$resource->solution) }}">
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="font-semibold text-sm text-gray-700">Detailed Steps</label>
                    <textarea name="resources[{{ $resourceIndex }}][details]"
                        class="input mt-1 w-full h-32 border-gray-300 rounded-md shadow-sm"
                    >{{ old('resources.' . $resourceIndex . '.details',$resource->details) }}</textarea>
                </div>

            </div>
        </div>
        @php $resourceIndex++; @endphp
        @endforeach
    </div>

    <div class="flex items-center gap-4 mt-4">
        <button type="button" id="add-resource"
            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition">
            + Add Cause & Solution
        </button>

        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-xl transition">
            Save Changes
        </button>
    </div>

</form>

</div>

<script>
let resourceCounter = {{ $guide->resources->count() }};
const wrapper = document.getElementById('resource-wrapper');

function getResourceHtml(index) {
    return `
    <div class="border p-6 mt-4 rounded-lg bg-white shadow-inner resource-block relative">
        <div class="flex justify-between items-center mb-4">
            <span class="font-bold text-gray-600 text-sm">New Block</span>
            <button type="button" class="remove-resource text-red-600 hover:text-red-800 text-sm font-semibold flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Remove
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="font-semibold text-sm text-gray-700">Cause</label>
                <input name="resources[${index}][cause]"
                    class="input mt-1 w-full border-gray-300 rounded-md shadow-sm"
                    placeholder="e.g. software bug">
            </div>

            <div>
                <label class="font-semibold text-sm text-gray-700">Solution</label>
                <input name="resources[${index}][solution]"
                    class="input mt-1 w-full border-gray-300 rounded-md shadow-sm"
                    placeholder="update phone">
            </div>

            <div class="col-span-1 md:col-span-2">
                <label class="font-semibold text-sm text-gray-700">Detailed Steps</label>
                <textarea name="resources[${index}][details]"
                    class="input mt-1 w-full h-32 border-gray-300 rounded-md shadow-sm"
                    placeholder="Write detailed troubleshooting steps…"
                ></textarea>
            </div>

        </div>
    </div>
    `;
}

// Add new resource block
document.getElementById('add-resource').onclick = () => {
    wrapper.insertAdjacentHTML('beforeend', getResourceHtml(resourceCounter));
    resourceCounter++;
};

// Event delegation to handle clicking "Remove" on both existing and new blocks
wrapper.addEventListener('click', (e) => {
    const removeBtn = e.target.closest('.remove-resource');
    if (removeBtn) {
        const block = removeBtn.closest('.resource-block');
        if (block) {
            block.remove();
        }
    }
});
</script>

@endsection