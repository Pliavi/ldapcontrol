@if(Session::has('error'))
    <?php $error = Session::pull('error'); ?>
    <div class="alert alert-error">
    @if(is_array($error))
    <b>Alguns problemas foram encontrados:</b>
        <ul>
        @foreach($error as $field => $err)
            @foreach($err as $e)
                <li>{{ $e }}</li>
            @endforeach
        @endforeach
        </ul>
    @else
        {{ $error }}
    @endif
    </div>
@endif
@if(Session::has('success'))
    <div class="alert alert-done">{{ Session::pull('success') }}</div>                    
@endif