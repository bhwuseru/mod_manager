<div>
    <form method="post" action="{{ route('mod') }}">
        @csrf
        <label for="directory_name">
            mod名
            <input type="text" wire:model='searchModName' name="directory_name">
        </label>
    </form>
    <table>
        <thead>
            <th>名前</th>
            <th>ClientTool</th>
            <th>プラグイン</th>
        </thead>
        <tbody>
            @foreach ($mods as $mod)
            @if($mod->isSeparator)
                <tr style="background-color: lightblue;">
            @else
                <tr>
            @endif
            <td>{{ $mod->directoryName}}</td>
                {{-- <td>{{ $mod->containClientTools  ? '◯' : '✘' }}</td> --}}
                {{-- <td>{{ $mod->pluginFiles->isEmpty() ? '◯' : '✗'}}</td> --}}
            </tr>
            @endforeach
        </tbody>
        </table>

</div>
