@extends('admin.layout')

@section('title','Create Guide')

@section('content')

<div class="bg-white p-6 rounded-xl shadow-lg">

<h2 class="text-3xl font-extrabold mb-6 text-gray-800">Create New Guide</h2>

<form method="POST" action="{{ route('admin.guides.store') }}" class="space-y-6">
@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
    <!-- Main Guide Details -->
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

<div id="resource-wrapper">
    <!-- Resource Blocks Go Here -->
</div>

<button type="button" id="add-resource"
    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-150 ease-in-out shadow-md">
    + Add Cause & Solution
</button>

<button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 ease-in-out shadow-xl">
    Save Guide
</button>


</form>
</div>

<script>
// --- JAVASCRIPT FIX ---
// The HTML template is now a pure JS string defined entirely within the script block.

let resourceCounter = 0;
const resourceWrapper = document.getElementById('resource-wrapper');

// Function to generate the resource HTML string with the dynamic index
function getResourceHtml(index) {
return `
<div class="border p-6 mt-4 rounded-lg bg-white shadow-inner resource-block">
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            &lt;div&gt;
                &lt;label class=&quot;font-semibold text-sm block text-gray-700&quot;&gt;Cause&lt;/label&gt;
                &lt;input name=&quot;resources[${index}][cause]&quot; class=&quot;input mt-1 block w-full border-gray-300 rounded-md shadow-sm&quot; placeholder=&quot;e.g. software bug&quot;&gt;
            &lt;/div&gt;

            &lt;div&gt;
                &lt;label class=&quot;font-semibold text-sm block text-gray-700&quot;&gt;Solution&lt;/label&gt;
                &lt;input name=&quot;resources[${index}][solution]&quot; class=&quot;input mt-1 block w-full border-gray-300 rounded-md shadow-sm&quot; placeholder=&quot;update phone&quot;&gt;
            &lt;/div&gt;

            &lt;!-- FULL WIDTH TEXTAREA --&gt;
            &lt;div class=&quot;col-span-1 md:col-span-2&quot;&gt;
                &lt;label class=&quot;font-semibold text-sm block text-gray-700&quot;&gt;Detailed Steps&lt;/label&gt;
                &lt;textarea
                    name=&quot;resources[${index}][details]&quot;
                    class=&quot;input h-32 w-full mt-1 border-gray-300 rounded-md shadow-sm&quot;
                    placeholder=&quot;Write detailed troubleshooting steps…&quot;
                &gt;&lt;/textarea&gt;


            </div>

        </div>
    </div>
`;


}

document.getElementById('add-resource').onclick = () => {
const index = resourceCounter;

let box = document.createElement('div');
// Call the function to get the clean HTML string
box.innerHTML = getResourceHtml(index);

resourceWrapper.appendChild(box);

resourceCounter++;


}

document.addEventListener('DOMContentLoaded', () => {
// Start with one resource block visible for better UX
document.getElementById('add-resource').click();
});
</script>

@endsection