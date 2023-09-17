@if(session('error'))
<div>{{ session('error') }}</div>
@endif

<a href="{{ route('mods') }}">テスト</a>
