{!! $body !!}

<table>
    <tbody>
        @foreach($data as $label => $value)
            <tr>
                <td>{{ $label }}</td>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ $url }}" target="_blank">View in CMS</a>
