@if(session('error'))
<div>{{ session('error') }}</div>
@endif

{{-- <form action="{{ route('mod') }}" method="POST"> --}}
<a href="{{ route('mod') }}">テスト</a>
{{-- </form> --}}
