@if(0 < count($modDirs))
<ul>
@foreach ($modDirs as $mod)
    <li>
        <a>{{ $mod }}</a>
    </li>
@endforeach
</ul>
@endif
