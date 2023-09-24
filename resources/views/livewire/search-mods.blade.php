<div>
    <form method="post" action="{{ route('mod') }}">
        @csrf
        <label for="directory_name">
            mod名
            <input type="text" wire:model='searchModName' name="directory_name">
        </label>
    </form>
    @if(0 < count($mods)) <table>
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

                <td>{{ $mod->directory_name }}</td>
                <td>{{ empty($mod->calienteTools) ? '✘' : '◯' }}</td>
                <td>{{ 0 < count($mod->pluginFiles) ? '◯' : '✗'}}</td>
            </tr>
            @endforeach
        </tbody>
        </table>
        @endif

</div>
