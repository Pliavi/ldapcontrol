@if(Session::has('error'))
    <div class="alert alert-error">{{ Session::get('error') }}</div>
@endif
@if(Session::has('success'))
    <div class="alert alert-done">{{ Session::get('success') }}</div>                    
@endif