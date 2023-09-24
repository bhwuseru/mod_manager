@if(session('error'))
<div>{{ session('error') }}</div>
@endif

<body>
    @livewire('search-mods')
</body>

{{-- <form action="{{ route('mod') }}" method="POST"> --}}
<a href="{{ route('mod') }}">テスト</a>
{{-- </form> --}}
