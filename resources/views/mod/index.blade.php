@if(0 < count($mods)) <table>
    <thead>
        <th>名前</th>
        <th>ClientTool</th>
        <th>プラグイン</th>
    </thead>
    <tbody>
        @foreach ($mods as $dirName => $body)
        <tr>
            <td>{{ $dirName }}</td>
            <td>{{ empty($body->calienteTools) ? '✘' : '◯' }}</td>
            <td>{{ 0 < count($body->pluginFiles) ? '◯' : '✗'}}</td>
        </tr>
        @endforeach
    </tbody>
    </table>
    @endif
