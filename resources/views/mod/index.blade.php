@if(0 < count($mods))
@foreach ($mods as $dirName => $pluginFiles)
<ul>
    <li>
        <span>{{ $dirName }}</span>
        @foreach ($pluginFiles as $file)
        <a>{{ $file }}</a>
        @endforeach
    </li>
</ul>
@endforeach
@endif
