<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Docs</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div>
    </body>
</html>

{{--Orders Page--}}
{{--                            <tr>--}}
{{--                                <td>--}}
{{--                                    <div class="checkbox">--}}
{{--                                        <input type="checkbox" class="checkSingle" id="check-2" >--}}
{{--                                        <label for="check-2"></label>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <a href="{{route('order.detail',1)}}">#2856</a>--}}
{{--                                </td>--}}
{{--                                <td>2020-01-13</td>--}}
{{--                                <td>Ali09@gmail.com</td>--}}
{{--                                <td>--}}
{{--                                    <div class="button-group">--}}
{{--                                        <a href="" class="btn waves-effect waves-light btn-rounded btn-xs btn-info send_email">--}}
{{--                                            Send--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    2020-01-17--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <span class="badge badge-pill bdg-sky designer">Dhy</span>--}}
{{--                                </td>--}}
{{--                                <td align="center">--}}
{{--                                   <div class="update_div">--}}
{{--                                       <span class="mdi mdi-alert-outline text-white display-6"></span>--}}
{{--                                   </div>--}}
{{--                                    <h6 class="text-warning"><b>Update</b></h6>--}}
{{--                                </td>--}}

{{--                                <td align="center">--}}
{{--                                    <div>--}}
{{--                                        <span class="mdi mdi-palette pallete"></span>--}}
{{--                                    </div>--}}
{{--                                    <h6 class="pallete_text"><b>CHANGE STYLE</b></h6>--}}
{{--                                </td>--}}

{{--                                <td style="background: #a53838;color:#ffff">--}}
{{--                                    <div class="dropdown">--}}
{{--                                        <span class="pr-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">In-process</span>--}}

{{--                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">--}}
{{--                                            <a class="dropdown-item text-primary change_status" >New Order</a>--}}
{{--                                            <a class="dropdown-item text-danger change_status" >In-process Order</a>--}}
{{--                                            <a class="dropdown-item text-success change_status" >Completed Order</a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                            </tr>--}}

{{--                            <tr>--}}
{{--                                <td>--}}
{{--                                    <div class="checkbox">--}}
{{--                                        <input type="checkbox" class="checkSingle" id="check-3" >--}}
{{--                                        <label for="check-3"></label>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <a href="{{route('order.detail',1)}}">#2823</a>--}}
{{--                                </td>--}}
{{--                                <td>2020-01-08</td>--}}
{{--                                <td>katdunn02@gmail.com</td>--}}
{{--                                <td>--}}
{{--                                    <div class="button-group">--}}
{{--                                        <a href="" class="btn waves-effect waves-light btn-rounded btn-xs btn-info send_email">--}}
{{--                                            Send--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    2020-01-19--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <span class="badge badge-pill bdg-pink designer">Maria</span>--}}
{{--                                </td>--}}
{{--                                <td align="center">--}}
{{--                                    <div class="approved_div">--}}
{{--                                        <span class="mdi mdi-check-circle-outline text-white display-6"></span>--}}
{{--                                    </div>--}}
{{--                                    <h6 class="text_active"><b>Approved</b></h6>--}}
{{--                                </td>--}}

{{--                                <td align="center">--}}
{{--                                    <div>--}}
{{--                                        <span class="mdi mdi-tooltip-edit fix_request"></span>--}}
{{--                                    </div>--}}
{{--                                    <h6 class="fix_text"><b>FIX REQUEST</b></h6>--}}
{{--                                </td>--}}

{{--                                <td style="background: #449d44;color:#ffff">--}}
{{--                                        <div class="dropdown">--}}
{{--                                            <span class="pr-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Completed</span>--}}

{{--                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">--}}
{{--                                                <a class="dropdown-item text-primary change_status" >New Order</a>--}}
{{--                                                <a class="dropdown-item text-danger change_status" >In-process Order</a>--}}
{{--                                                <a class="dropdown-item text-success change_status" >Completed Order</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                </td>--}}
{{--                            </tr>--}}

{{--                            <tr>--}}
{{--                                <td>--}}
{{--                                    <div class="checkbox">--}}
{{--                                        <input type="checkbox" class="checkSingle" id="check-4" >--}}
{{--                                        <label for="check-4"></label>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <a href="{{route('order.detail',1)}}">#2898</a>--}}
{{--                                </td>--}}
{{--                                <td>2020-01-01</td>--}}
{{--                                <td>john03@gmail.com</td>--}}
{{--                                <td>--}}
{{--                                    <div class="button-group">--}}
{{--                                        <a href="" class="btn waves-effect waves-light btn-rounded btn-xs btn-info send_email">--}}
{{--                                            Send--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    2020-01-29--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <span class="badge badge-pill bdg-yellow designer">Asaf</span>--}}
{{--                                </td>--}}
{{--                                <td align="center">--}}
{{--                                    <div class="cir">--}}
{{--                                        <span class="rec"></span>--}}
{{--                                    </div>--}}
{{--                                    <h6 class="not_completed"><b>No Design</b></h6>--}}

{{--                                </td>--}}

{{--                                <td align="center">--}}
{{--                                    <div>--}}
{{--                                        <span class="mdi mdi-message-text message"></span>--}}
{{--                                    </div>--}}
{{--                                    <h6 class="message_text"><b>Customer Message</b></h6>--}}
{{--                                </td>--}}

{{--                                <td style="background: #a53838;color:#ffff">--}}
{{--                                        <div class="dropdown">--}}
{{--                                            <span class="pr-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">In-process</span>--}}

{{--                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">--}}
{{--                                                <a class="dropdown-item text-primary change_status" >New Order</a>--}}
{{--                                                <a class="dropdown-item text-danger change_status" >In-process Order</a>--}}
{{--                                                <a class="dropdown-item text-success change_status" >Completed Order</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                </td>--}}
{{--                            </tr>--}}

{{--                            <tr>--}}
{{--                                <td>--}}
{{--                                    <div class="checkbox">--}}
{{--                                        <input type="checkbox" class="checkSingle" id="check-5" >--}}
{{--                                        <label for="check-5"></label>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <a href="{{route('order.detail',1)}}">#2847</a>--}}
{{--                                </td>--}}
{{--                                <td>2020-01-15</td>--}}
{{--                                <td>Adam04@gmail.com</td>--}}
{{--                                <td>--}}
{{--                                    <div class="button-group">--}}
{{--                                        <a href="" class="btn waves-effect waves-light btn-rounded btn-xs btn-info send_email">--}}
{{--                                            Send--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    2020-01-04--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <span class="badge badge-pill bdg-purple designer">Ihsan</span>--}}
{{--                                </td>--}}
{{--                                <td align="center">--}}
{{--                                    <div class="approved_div">--}}
{{--                                        <span class="mdi mdi-check-circle-outline check_mark"></span>--}}
{{--                                    </div>--}}
{{--                                    <h6 class="text_active"><b>Approved</b></h6>--}}
{{--                                </td>--}}

{{--                                <td>--}}

{{--                                </td>--}}

{{--                                <td style="background: #a53838;color:#ffff">--}}
{{--                                        <div class="dropdown">--}}
{{--                                            <span class="pr-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">In-process</span>--}}

{{--                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">--}}
{{--                                                <a class="dropdown-item text-primary change_status" >New Order</a>--}}
{{--                                                <a class="dropdown-item text-danger change_status" >In-process Order</a>--}}
{{--                                                <a class="dropdown-item text-success change_status" >Completed Order</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
