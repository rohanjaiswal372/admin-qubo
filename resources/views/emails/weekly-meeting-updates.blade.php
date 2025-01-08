<!-- Emails use the XHTML Strict doctype -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- The character set should be utf-8 -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- Enables media queries -->
    <meta name="viewport" content="width=device-width"/>
    <title>{{$title}}</title>
    <style>
        html, table {
            font-family: "Helvetica Neue", Helvetica, Helvetica, Arial, sans-serif
        }

        .body-wrap, body {
            background-color: #f6f6f6
        }

        .footer, .footer a {
            color: #999
        }

        .aligncenter, .btn-primary, .footer {
            text-align: center
        }

        .btn .badge, .btn .label {
            position: relative;
            top: -1px
        }

        .btn-primary, a.badge:focus, a.badge:hover, a.label:focus, a.label:hover {
            cursor: pointer;
            text-decoration: none
        }

        html {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-size: 14px
        }

        img {
            max-width: 100%
        }

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100% !important;
            height: 100%;
            line-height: 1.6
        }

        *, :after, :before {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box
        }

        table {
            border-collapse: collapse;
            border-spacing: 0
        }

        .alert, h1, h2, h3 {
            font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
        }

        table td {
            vertical-align: middle;
            padding: 0px;
            word-wrap: break-word;
            max-width: 300px;
        }

        table th {
            padding: 2px 5px 0 2px;
            font-size: 13px;
            height: 20px;
            font-weight: 700;
            border-bottom:1px solid #eee;

        }

        table tr {

        }

        .content, .content-wrap {
            padding: 9px
        }

        .body-wrap {
            width: 100%
        }

        .container {
            display: block !important;
            max-width: 100% !important;
            margin: 0 auto !important;
            clear: both !important
        }

        .clear, .footer {
            clear: both
        }

        .content {
            max-width: 80%;
            margin: 0 auto;
            display: block
        }

        .main {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 3px
        }

        .content-block {
            padding: 0 0 20px
        }

        .header {
            width: 100%;
            margin-bottom: 20px
        }

        .footer {
            width: 100%;
            padding: 20px
        }

        .footer a, .footer p, .footer td, .footer unsubscribe {
            font-size: 12px
        }

        .column-left, .column-right {
            float: left;
            width: 50%
        }

        h1, h2, h3 {
            color: #000;
            margin: 10px 0 5px;
            line-height: 1;
            font-weight: 400
        }

        h1 {
            font-size: 30px;
            font-weight: 500
        }

        h2 {
            font-size: 23px;
            margin-bottom:10px;
            font-weight: bold;
        }

        h3 {
            font-size: 20px
        }

        h4 {
            font-size: 14px;
            font-weight: 600
        }

        ol, p, ul {
            margin-bottom: 10px;
            font-weight: 400
        }

        ol li, p li, ul li {
            margin-left: 5px;
            list-style-position: inside
        }

        a {
            color: #348eda;
            text-decoration: underline
        }

        small, small a {
            font-size: 70%
        }

        .btn-primary {
            color: #FFF;
            background-color: #348eda;
            border: solid #348eda;
            border-width: 8px 14px;
            line-height: 1;
            font-weight: 700;
            display: inline-block;
            border-radius: 5px;
            text-transform: capitalize
        }

        .alert, .alert a {
            color: #333;
            font-weight: 500
        }
        .left{
            float:left;

        }
        .right{
            float:right;
        }

        .alignleft {
            text-align: left
        }

        .alert {
            float:left;
            font-size: 14px;
            padding: 2px 7px;
            text-align: center;
            border-radius: 5px
        }

        .alert a {
            text-decoration: none;
            font-size: 16px
        }

        .badge, .label {
            font-weight: 700;
            line-height: 1;
            white-space: nowrap;
            text-align: center;
            color: #333
        }

        .alert.alert-warning {
            background-color: #ff9f00
        }

        .alert.alert-bad {
            background-color: #d0021b
        }

        .alert.alert-good {
            background-color: #68b90f
        }

        .label {
            display: inline;
            padding: .15em .4em .2em;
            font-size: 75%;
            vertical-align: baseline;
            border-radius: .25em
        }

        a.label:focus, a.label:hover {
            color: #fff
        }

        .label:empty {
            display: none
        }

        .label-default {
            background-color: #ccc
        }

        .label-default[href]:focus, .label-default[href]:hover {
            background-color: #5e5e5e
        }

        .label-primary {
            background-color: #337ab7
        }

        .label-primary[href]:focus, .label-primary[href]:hover {
            background-color: #286090
        }

        .label-success {
            background-color: #5cb85c
        }

        .label-success[href]:focus, .label-success[href]:hover {
            background-color: #449d44
        }

        .label-info, .alert-info {
            background-color: #5bc0de
        }

        .label-info[href]:focus, .label-info[href]:hover {
            background-color: #31b0d5
        }

        .label-warning {
            background-color: #f0ad4e
        }

        .label-warning[href]:focus, .label-warning[href]:hover {
            background-color: #ec971f
        }

        .label-danger {
            background-color: #d9534f
        }

        .label-danger[href]:focus, .label-danger[href]:hover {
            background-color: #c9302c
        }

        .badge {
            display: inline-block;
            min-width: 10px;
            padding: 3px 7px;
            font-size: 12px;
            vertical-align: middle;
            background-color: #777;
            border-radius: 10px
        }

        .badge:empty {
            display: none
        }

        .btn-group-xs > .btn .badge, .btn-xs .badge {
            top: 0;
            padding: 1px 5px
        }

        a.badge:focus, a.badge:hover {
            color: #fff
        }

        img.logo, img.sponsor-logo {
            max-width: 130px
        }

        @media only screen and (max-width: 640px) {
            h1, h2, h3, h4 {
                font-weight: 600 !important;
                margin: 20px 0 5px !important
            }

            h1 {
                font-size: 22px !important
            }

            h2 {
                font-size: 18px !important
            }

            .container {
                width: 100% !important
            }

            .content {
                max-width: 100%;
            }

            .content, .aligncenter {
                text-align: left
            }

            h3 {
                font-size: 24px !important;
                font-weight: 700 !important;
                margin-top: 0 !important
            }
        }

        h3.heading {
            text-align: center;
            background-color: #f3f3f3;
            padding: 6px 0;
            border-top: #ccc 1px solid;
            border-bottom: #aaa 1px solid;
        }
    </style>
</head>
<body>
<table class="body-wrap">
    <tr class="aligncenter">
        <td>
            <img src="{{corp_image('/global/corp-logos/png/IONTV-logo-grey.png')}}" class="logo"/>
        </td>
        <td>
            <div class="aligncenter">
                <h3>
                    <a href="{{site_url('campaigns/','admin',FALSE,TRUE)}}"
                       target="_blank"
                       title="Campaigns" class="btn btn-primary">View/Edit Campaigns
                    </a></h3>
            </div>
        </td>
        <td></td>
        <td></td>
    </tr>
</table>

<table class="body-wrap">
    <tr class="aligncenter" style="background:#fff;">
        <td>
            <h2><u>For weekly updates meeting</u></h2>

           @if(count($starts_today))
                <h4><strong>Sponsorships: (New Starting {{ Carbon::today()->format('M jS') }} ) </h4>
            <ul>
                @foreach($starts_today as $campaign)
                    <li>{{$campaign->name}} ( {{  Carbon::parse($campaign->starts_at)->format('M jS')}} - {{ Carbon::parse($campaign->ends_at)->format('M jS') }} )  </li>
                @endforeach
            </ul>
            @endif
            @if(count($recent))
                <h4><strong>Sponsorships: (Currently Live) </h4>
            <ul>
                @foreach($recent as $campaign)
                    @if($campaign->running && !$campaign->expired)
                        <li>{{$campaign->name}} ( {{  Carbon::parse($campaign->starts_at)->format('M jS')}} - {{ Carbon::parse($campaign->ends_at)->format('M jS') }} )  </li>
                    @endif
                @endforeach
            </ul>
            @endif
            @if(count($starts_next_week))
            <h4><strong>Sponsorships: (Coming Up Next Week {{ Carbon::now()->startOfWeek()->addWeeks(1)->format('M jS') }} ) </h4>
            <ul>
                @foreach($starts_next_week as $campaign)
                    <li>{{$campaign->name}} ( {{  Carbon::parse($campaign->starts_at)->format('M jS')}} - {{ Carbon::parse($campaign->ends_at)->format('M jS') }} )  </li>
                @endforeach
            </ul>
            @endif
        </td>
    </tr>
    <tr class="footer">
        <td>
            <p>
                <small>This email is property of <a href="https://ionmedia.com" target="_blank">ION Media
                        Networks</a>
                    and is not to be used for any other purposes unless written permission is given.
                </small>
            </p>
        </td>
    </tr>

</table>
</body>
</html>


