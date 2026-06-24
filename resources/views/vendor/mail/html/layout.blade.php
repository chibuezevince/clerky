<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="color-scheme" content="dark">
    <meta name="supported-color-schemes" content="dark">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .content-cell {
                padding: 30px 24px !important;
            }

            .header-cell {
                padding: 28px 24px !important;
            }

            .footer-cell {
                padding: 20px 24px !important;
            }
        }

        :root {
            color-scheme: dark;
        }

        p {
            margin-top: 0;
        }

        p,
        ul,
        ol,
        blockquote {
            margin: 0 0 20px 0;
        }

        li {
            margin-bottom: 8px;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 20px 0;
        }

        h2 {
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 16px 0;
        }

        h3 {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 12px 0;
        }

        a {
            color: #F4FD3B;
            text-decoration: underline;
        }

        .break-all {
            word-break: break-all;
        }
    </style>
</head>

<body style="margin: 0; padding: 0; width: 100%; background-color: #0A0A0A; -webkit-text-size-adjust: none;">

    <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
        style="margin: 0; padding: 0; width: 100%; background-color: #0A0A0A;">
        <tr>
            <td align="center" style="padding: 40px 16px 40px;">

                {{-- Outer content wrapper --}}
                <table width="570" cellpadding="0" cellspacing="0" role="presentation"
                    style="margin: 0 auto; width: 570px;">

                    {{-- Header --}}
                    {{ $header ?? '' }}

                    {{-- Body card --}}
                    <tr>
                        <td style="padding: 0;">
                            <table class="inner-body" width="570" cellpadding="0" cellspacing="0" role="presentation"
                                style="margin: 0 auto; width: 570px; border-color: #1e1e1e; border: 1px solid #1E1E1E; overflow: hidden;">
                                <tr>
                                    <td class="content-cell" style="padding: 40px 48px;
                                        font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
                                        font-size: 15px; line-height: 1.7;
                                        color: #A1A1AA;">
                                        {{ Illuminate\Mail\Markdown::parse($slot) }}
                                        {{ $subcopy ?? '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    {{ $footer ?? '' }}

                </table>

            </td>
        </tr>
    </table>

</body>

</html>