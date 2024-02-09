{!! $body !!}

<table>
    <tbody>
        @foreach($data as $label => $value)
            <tr>
                <td
                    valign="top"
                    style="font-weight: 700; padding-bottom: 5px;"
                >
                    {{ $label }}
                </td>
                <td
                    valign="top"
                    style="padding-bottom: 5px; padding-left: 30px;"
                >
                    {{ $value }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div style="padding-top: 30px;">
    <a href="{{ $url }}" target="_blank">View in CMS</a>
</div>
