<!-- Emails use the XHTML Strict doctype -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- The character set should be utf-8 -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- Enables media queries -->
    <meta name="viewport" content="width=device-width"/>
    <title>{{$campaign->name}}</title>
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
            padding: 5px 5px 0 5px;
            word-wrap: break-word;
            max-width: 300px;
        }

        table th {
            padding: 2px 5px 0 2px;
            font-size: 13px;
            height: 20px;
            font-weight: 700;

        }

        table tr {

        }

        .content, .content-wrap {
            padding: 20px
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
            font-size: 24px
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

        small {
            font-size: 80%
        }

        .btn-primary {
            color: #FFF;
            background-color: #348eda;
            border: solid #348eda;
            border-width: 10px 20px;
            line-height: 2;
            font-weight: 700;
            display: inline-block;
            border-radius: 5px;
            text-transform: capitalize
        }

        .alert, .alert a {
            color: #333;
            font-weight: 500
        }

        .alignleft {
            text-align: left
        }

        .alert {
            font-size: 14px;
            padding: 5px 10px;
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
            padding: .2em .6em .3em;
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
            background-color: #777
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

        .label-info {
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
    <tr>
        <td>
            <div class="aligncenter"> @if($campaign->sponsor && $campaign->sponsor->logo)
                    <img class="sponsor-logo"
                         src="{{$campaign->sponsor->logo->url}}"/> @endif
                <h3><strong>Campaign:</strong>
                    <a href="{{site_url('campaigns/'.$campaign->id.'/edit/',(!\App::environment("production"))?'dev-admin':'admin',FALSE,TRUE)}}"
                       target="_blank"
                       title="Go to Campaign">{{$campaign->name}}
                    </a></h3>
            </div>
        </td>
    </tr>
    <tr>
        <td class="container">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content-wrap">
                            <div class="alignleft">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <th>
                                        Status:
                                    </th>
                                    <th>
                                        Owner:
                                    </th>
                                    <th>
                                        Sponsor:
                                    </th>
                                    <th>
                                        Start Date:
                                    </th>
                                    <th>
                                        End Date:
                                    </th>
                                    <tbody>
                                    <tr>
                                        @if($campaign->status)
                                            <td>
                                                <span class="alert @if($campaign->status->status_id == 'approved') alert-good @else alert-warning @endif">{{title_case($campaign->status->status_id)}}</span>
                                                @if($campaign->approved)
                                                    <h5> Approved By: <strong>{{$campaign->approver->fullName}}</strong>
                                                        <span class="small"> on
                                                        the {{Carbon::parse($campaign->status->updated_at)->format('jS \o\f F, Y g:i:s a')}}</span>
                                                    </h5>@endif
                                            </td>
                                            <td>
                                                {{$campaign->owner->fullname}}
                                            </td>
                                            <td>
                                                @if($campaign->sponsor)
                                                    <a href="{{site_url('sponsors/'.$campaign->sponsor->id.'/edit/',(!\App::environment("production"))?'dev-admin':'admin',FALSE,TRUE)}}"
                                                       title="edit sponsor">
                                                        <h4>{{$campaign->sponsor->name}}</h4>
                                                    </a>
                                                @else
                                                    No Sponsor
                                                @endif
                                            </td>
                                            <td>
                                                {{Carbon::parse($campaign->starts_at)->format('m/d/y')}}
                                            </td>
                                            <td>
                                                {{Carbon::parse($campaign->ends_at)->format('m/d/y')}}
                                            </td>
                                        @else
                                            <td colspan="3">CAMPAIGN HAS BEEN DELETED</td>
                                        @endif
                                    </tr>
                                    </tbody>
                                </table>
                                @if(count($campaign->comments))
                                    <h3 class="heading"><strong>Comments:</strong></h3>
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <thead>
                                        <th>User</th>
                                        <th>Comment</th>
                                        <th>Time</th>
                                        </thead>
                                        <tbody>
                                        @foreach($campaign->comments->reverse()->slice(0,6) as $comment)
                                            <tr @if($loop->index & 1) style="background-color:#dedede" @endif>
                                                <td><strong>
                                                        <small>{{ str_limit($comment->commented->fullName,20) }}
                                                    </strong></small>&nbsp;
                                                </td>
                                                <td>
                                                    <small>{{$comment->comment}}</small>
                                                </td>
                                                <td>
                                                    <small>{{Carbon::parse($comment->updated_at)->format('m/d/y h:i:s A')}}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                                @if(strlen($campaign->description)> 0)
                                    <h3 class="heading"><strong>Description:</strong></h3>
                                    <p>{!! $campaign->description !!}</p>
                                @endif

                                @if(count($campaign->items))
                                    <h3 class="heading"><strong>Items</strong></h3>
                                    <small>
                                        @include('campaigns.partials.associated-items',['campaign'=>$campaign])
                                    </small>
                                @endif
                                <br>
                                <hr>
                                <p class="aligncenter">
                                    <small>This campaign was last updated
                                        on {{Carbon::parse($campaign->updated_at)->format('m/d/y')}}</small>
                                </p>
                                <p class=" aligncenter">
                                    <small><em>This email was also sent
                                            to @foreach($campaign->followers as $follower) @if($loop->last) and @endif
                                            <a
                                                    href="mailto:{{$follower->user->email}}"
                                                    target="_blank"
                                                    title="{{$follower->user->email}}">{{$follower->user->fullname}}</a>@if(!$loop->last)
                                                , @endif @endforeach </em></small>
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
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


