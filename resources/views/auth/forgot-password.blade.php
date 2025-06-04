@extends('layouts.base')
@section('content')
<table border="0" cellpadding="0" cellspacing="0" class="text_block block-4" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
    <tr>
        <td class="pad" style="padding-bottom:10px;padding-left:45px;padding-right:45px;padding-top:10px;">
            <div style="font-family: sans-serif">
                <div class="" style="font-size: 12px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; text-align: center; mso-line-height-alt: 18px; color: #393d47; line-height: 1.5;">
                    <p style="margin: 0; text-align: left; mso-line-height-alt: 18px;"><strong><span style="font-size:18px;color:#000000;">Reset Password</span></strong></p>
                </div>
            </div>
        </td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="text_block block-4" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
    <tr>
        <td class="pad" style="padding-bottom:10px;padding-left:45px;padding-right:45px;padding-top:10px;">
            <div style="font-family: sans-serif">
                <div class="" style="font-size: 12px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; text-align: center; mso-line-height-alt: 18px; color: #393d47; line-height: 1.5;">
                    <p style="margin: 0; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:14px;color:#000000;">Kami telah menerima permintaan reset password pada akun Anda.</span></p>
                    <p style="margin: 0; text-align: left; mso-line-height-alt: 21px;"><span style="font-size:14px;color:#000000;">Untuk memulai reset password Anda, silakan akses melalui tombol di bawah ini.</span></p>
                </div>
            </div>
        </td>
    </tr>
</table>
<table border="0" cellpadding="10" cellspacing="0" class="button_block block-5" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
    <tr>
        <td class="pad">
            <div align="center" class="alignment"><!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{$redirectUrl}}" style="height:50px;width:203px;v-text-anchor:middle;" arcsize="0%" strokeweight="0.75pt" strokecolor="#2bc012" fillcolor="#2bc012"><w:anchorlock/><v:textbox inset="0px,0px,0px,0px"><center style="color:#ffffff; font-family:Arial, sans-serif; font-size:14px"><![endif]--><a href="{{$redirectUrl}}" style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#895700;border-radius:0px;width:auto;border-top:1px solid transparent;font-weight:400;border-right:1px solid transparent;border-bottom:1px solid transparent;border-left:1px solid transparent;padding-top:10px;padding-bottom:10px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;text-align:center;mso-border-alt:none;word-break:keep-all;" target="_blank"><span style="padding-left:40px;padding-right:40px;font-size:14px;display:inline-block;letter-spacing:normal;"><span dir="ltr" style="word-break: break-word; line-height: 28px;">Reset Password</span></span></a><!--[if mso]></center></v:textbox></v:roundrect><![endif]--></div>
        </td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="text_block block-6" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
    <tr>
        <td class="pad" style="padding-bottom:10px;padding-left:45px;padding-right:45px;padding-top:10px;">
            <div style="font-family: sans-serif">
                <div class="" style="text-align: left; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; font-size: 12px; mso-line-height-alt: 18px; color: #0068a5; line-height: 1.5;">
                    <p style="margin: 0; mso-line-height-alt: 21px;"><span style="color:#000000;font-size:14px;">Jika Anda mengalami kendala dengan tombol di atas, Anda bisa klik link di bawah ini:</span></p>
                    <p style="margin: 0; mso-line-height-alt: 18px;"> </p>
                    <p style="margin: 0; mso-line-height-alt: 21px;"><span style="color:#0068a5;font-size:14px;"><span style="color:#000000;"><a href="{{$redirectUrl}}" style="color: #895700;">{{$redirectUrl}}</a></span></span></p>
                </div>
            </div>
        </td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="text_block block-6" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
    <tr>
        <td class="pad" style="padding-bottom:10px;padding-left:45px;padding-right:45px;padding-top:10px;">
            <div style="font-family: sans-serif">
                <div class="" style="text-align: left; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; font-size: 12px; mso-line-height-alt: 18px; color: #0068a5; line-height: 1.5;">
                    <p style="margin: 0; mso-line-height-alt: 21px;"><span style="color:#000000;font-size:14px;">Terimakasih,</span></p>
                    <p style="margin: 0; mso-line-height-alt: 18px;"> </p>
                    <p style="margin: 0; mso-line-height-alt: 21px;"><span style="color:#000000;font-size:14px;"><strong>SIMDATUK<strong></span></p>
                </div>
            </div>
        </td>
    </tr>
</table>
@endsection
