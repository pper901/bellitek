@extends('admin.layout')

@section('title','Create Guide')

@section('content')

<div class="space-y-10">
    <div class="bg-white p-6 rounded-xl shadow-lg">

        <h2 class="text-3xl font-extrabold mb-6 text-gray-800">Create New Guide</h2>

        <form method="POST" action="{{ route('admin.guides.store') }}" class="space-y-6">
            @csrf

            <!-- Main Guide Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-gray-200 rounded-lg bg-gray-50">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Device</label>
                    <input name="device" class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Phone / Laptop">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <input name="category" class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Android / iPhone">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Brand</label>
                    <input name="brand" class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Samsung / HP">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Series</label>
                    <input name="series" class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="S Series">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Model</label>
                    <input name="model" class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Galaxy S25">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Issue</label>
                    <input name="issue" class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Screen has lines">
                </div>
            </div>

            <h3 class="font-bold text-xl mt-8 mb-2 border-b pb-1">Causes & Solutions</h3>

            <div id="resource-wrapper"></div>

            <button type="button" id="add-resource"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-150 ease-in-out shadow-md">
                + Add Cause & Solution
            </button>


            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 ease-in-out shadow-xl">
                Save Guide
            </button>

        </form>
        <div class="bg-white p-6 m-5 rounded-xl shadow-lg border-t-4 border-indigo-600">
            <h3 class="text-2xl font-bold mb-2 text-gray-800">Import via JSON</h3>
            <p class="text-sm text-gray-500 mb-4">Paste raw JSON data directly to quickly create a guide with multiple resources.</p>

            <form method="POST" action="{{ route('admin.guides.jsonstore') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">JSON Payload</label>
                    <textarea 
                        name="json_data" 
                        rows="10" 
                        class="font-mono text-sm w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder='{
    "device": "Phone",
    "category": "Android",
    "brand": "Samsung",
    "series": "S Series",
    "model": "Galaxy S25",
    "issue": "Screen has lines",
    "youtube_url": "https://www.youtube.com/watch?v=xxxx",
    "resources": [
        {
        "cause": "Software bug",
        "solution": "Update phone",
        "details": "Detailed step-by-step instructions..."
        }
    ]
    }'></textarea>
                    @error('json_data')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg transition duration-150 ease-in-out shadow-md">
                    Submit JSON Guide
                </button>
            </form>
        </div>
    </div>
</div>
<script>
let resourceCounter = 0;
const resourceWrapper = document.getElementById('resource-wrapper');

// Generates clean resource block HTML
function getResourceHtml(index) {
    return `
        <div class="border p-6 mt-4 rounded-lg bg-white shadow-inner resource-block">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="font-semibold text-sm block text-gray-700">Cause</label>
                    <input name="resources[${index}][cause]" 
                           class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                           placeholder="e.g. software bug">
                </div>

                <div>
                    <label class="font-semibold text-sm block text-gray-700">Solution</label>
                    <input name="resources[${index}][solution]" 
                           class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                           placeholder="update phone">
                </div>

                <div class="md:col-span-2">
                    <label class="font-semibold text-sm block text-gray-700">Detailed Steps</label>
                    <textarea name="resources[${index}][details]"
                              class="input h-40 w-full mt-1 border-gray-300 rounded-md shadow-sm"
                              placeholder="Write detailed troubleshooting steps…"></textarea>
                </div>

            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">
                    YouTube Video (optional)
                </label>
                <input
                    name="youtube_url"
                    class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    placeholder="https://www.youtube.com/watch?v=xxxx"
                >
                <p class="text-xs text-gray-500 mt-1">
                    Paste a normal YouTube link. It will be embedded automatically.
                </p>
            </div>
        </div>
    `;
}

document.getElementById('add-resource').onclick = () => {
    let div = document.createElement('div');
    div.innerHTML = getResourceHtml(resourceCounter);
    resourceWrapper.appendChild(div);
    resourceCounter++;
};

// Add first block automatically
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('add-resource').click();
});
</script>

@endsection
