<tr>
    <td>
        {{-- Spacer --}}
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td style="height: 24px;"></td>
            </tr>
        </table>

        {{-- Footer content --}}
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td class="footer-cell" style="padding: 0 48px 40px;
                           font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
                           font-size: 12px;
                           line-height: 1.6;
                           color: #71717A;
                           text-align: center;">

                    {{-- Divider --}}
                    <div style="width: 100%; height: 1px; background-color: #1E1E1E; margin-bottom: 24px;"></div>

                    <p style="margin: 0 0 6px 0;">
                        &copy; {{ date('Y') }}
                        <span style="color: #A1A1AA; font-weight: 500;">{{ config('app.name') }}</span>.
                        All rights reserved.
                    </p>
                    <p style="margin: 0;">
                        If you did not request this email, no action is required.
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>