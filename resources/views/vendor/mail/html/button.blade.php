@props(['url', 'color' => 'primary', 'align' => 'center'])
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin: 28px 0;">
    <tr>
        <td align="{{ $align }}">
            @php
                $bg = match ($color) {
                    'success' => '#22C55E',
                    'error' => '#EF4444',
                    default => '#F4FD3B',
                };
                $text = match ($color) {
                    'success', 'error' => '#FFFFFF',
                    default => '#0A0A0A',
                };
            @endphp
            <a href="{{ $url }}" style="display: inline-block;
                      background-color: {{ $bg }};
                      color: {{ $text }};
                      font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
                      font-size: 14px;
                      font-weight: 600;
                      letter-spacing: 0.1px;
                      text-decoration: none;
                      padding: 13px 28px;
                      border-radius: 8px;
                      mso-padding-alt: 0;
                      text-align: center;">
                {{ $slot }}
            </a>
        </td>
    </tr>
</table>