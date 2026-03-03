<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $classroom->title }} | Bellitek Classes</title>
    
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 h-screen flex flex-col overflow-hidden">
    <form id="userInfoForm" style="display:none;">
        <input type="hidden" id="name" name="userName" value="{{ auth()->user()->name }}">
        <input type="hidden" id="classTitle" name="classTitle" value="{{ $classroom->title }}">
        <input type="hidden" id="classUuid" name="classUuid" value="{{ $classroom->uuid }}">
    </form>
  <div class="modal-panel">
    <div id="x-close" style="display: inherit; justify-content: flex-end; cursor: pointer; margin-bottom: 10px; font-weight: bold;">
      <span style="background-color: rgb(1, 99, 66); padding: 5px; border-radius: 50%; width: 10px; height: 10px; text-align: center; display: flex; align-items: center; color: white;">x</span>
    </div>
  </div>
	<header><h3>{{ $classroom->title }} </h3></header>
        <div class="main-panel">
        	<div class="f-panel">
            	<div class="f-item" id="preview" onscroll="handleScroll(this)"></div>
                <div class="s-item" id="preview-control">
                	<button id="endClassBtn" data-url="{{ route('lecturer.classes.end', $classroom) }}">
                      End Class
                  </button>
                </div>
              <div class="u-item">
                <div id="upload-data"></div>
                <div class="u-item-hide">
                  <label for="fileInput">Choose a file:</label>
                  <input type="file" id="fileInput">
                  <button class="rs-btn" id="u-btn">Upload</button>
                </div>
                <div class="u-item-hide">
                  <label for="fileInput" style="margin-right: 5px;">Visit a link:</label>
                  <input type="text" id="linkInput">
                  <button class="rs-btn" id="v-btn">Visit</button>
                </div>
                <div class="lg-rs-btn">
                  <button class="rs-btn" onclick="showItem(0)">Upload A File</button>
                  <button class="rs-btn" onclick="showItem(1)">Visit A Resource Website</button>
                  <button class="rs-btn" id="editResource">Create Editable Resource</button>
                </div>
                
              </div>
            </div>
            <div class="s-panel" id="targetDiv1" onscroll="handleScroll(this)">
            	<!-- <div class="t-item"></div> -->
            </div>
            <div class="t-panel" id="t-panel">
            	<div class="fo-item" id="chat" onscroll="handleScroll(this)"></div>
                <div class="fi-item">
                	<!-- <form action="/class.html" method="post"> -->
                    	<input type="text" class="chat" name="chatMessage" id="message">
                        <input type="submit" class="chat-btn" name="chatBtn" id="chatBtn" value="Send" onclick="setMessage()">
                    <!-- </form> -->
                </div>
            </div>
        </div>

    <footer class="bg-white border-t text-center py-2 text-xs text-gray-400 shrink-0">
        © {{ date('Y') }} Bellitek Classes • Secure Classroom Environment
    </footer>

<script>
    function handleScroll(container) {
      if (container.scrollHeight > container.clientHeight) {
        container.style.overflowY = 'scroll';
      } else {
        container.style.overflowY = 'auto';
      }
    }

    function showItem(no){
      var items = document.querySelectorAll(".u-item-hide");
      items.forEach(element => {
        element.style.display = "none";
      });
      items[no].style.display = "flex";
    }
    
  </script>

    {{-- Static JS assets --}}
    <script src="{{ asset('js/lengine.js') }}"></script>
    <script src="{{ asset('js/adapter-latest.js') }}"></script>

</body>
</html>