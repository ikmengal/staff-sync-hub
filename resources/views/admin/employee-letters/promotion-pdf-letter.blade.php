<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $model->title }}</title>
        <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
        <style>
            * {
                -webkit-print-color-adjust: exact !important; /*Chrome, Safari */
                color-adjust: exact !important;  /*Firefox*/
            }
            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 100;
                src: url('admin/assets/vendor/fonts/montserrat/Montserrat-Thin.ttf')  format('ttf');
            }
            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 200;
                src: url('admin/assets/vendor/fonts/montserrat/Montserrat-ExtraLight.ttf')  format('ttf');
            }
            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 300;
                src: url('admin/assets/vendor/fonts/montserrat/Montserrat-Light.ttf')  format('ttf');
            }
            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 400;
                src: url('admin/assets/vendor/fonts/montserrat/Montserrat-Regular.ttf')  format('ttf');
            }
            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 500;
                src: url('admin/assets/vendor/fonts/montserrat/Montserrat-Medium.ttf')  format('ttf');
            }
            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 600;
                src: url('admin/assets/vendor/fonts/montserrat/Montserrat-Bold.ttf')  format('ttf');
            }
            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 700;
                src: url('admin/assets/vendor/fonts/montserrat/Montserrat-SemiBold.ttf')  format('ttf');
            }
            body {
                margin: 0;
                padding: 0;
                font-family: 'Montserrat', sans-serif !important;
                position:relative;
            }
            body:after{
                    position:absolute;
                    content:'';
                    top:0;
                    left:50%;
                    transform:translateX(-50%);
                    height:5px;
                    width:80%;
                    background:red;
                }
        
            .pdf-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 130px; /* Set header height  */
            }
            footer{
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0px;
                width:480px;
                height: 200px; 
            }
            @page {
                margin: 0cm;
                size: A3;
            }
            .pdfcontent {
                /*background: url('public/admin/letters/middle-icon.png');*/
                /*background-repeat: no-repeat;*/
                /*background-size: contain;*/
                /*background-position: center;*/
                /*position:relative;*/
                z-index:1;
            }
            .pdfcontent .bg {
                /*content: '';*/
                /*background: url(admin/letters/middle-icon.png);*/
                /*background-repeat: no-repeat;*/
                /*background-size: contain;*/
                /*background-position: center;*/
                position: absolute;
                left: 0;
                top: 50%;
                transform:translateY(-50%);
                width: 100%;
                height: 100%;
                z-index: -1;
                opacity: .2;
                text-align:center;
            }
            .pdfcontent .bg img{
                width:80%;
            }
            .left{
                width:50%;
                float:left;
            }
            .right {
                width: 50%;
                float: right;
                text-align: right;
            }
            .pdfcontent-section .date {
                 color: #000;
                    font-weight: 700;
                    text-align: right;
                    font-size: 18px;
            }
            .pdfcontent-section .letter-heading {
                border-bottom: 2px solid #000;
                display: table;
                color: #201f42;
                font-weight: 700;
            }
            .pdfcontent-section p {
                color: #000;
                direction: ltr;
                font-size: 17px;
                line-height: 180%;
            }
            .pdfsign-section h6{
                color: #000000;
                font-size: 14px;
                font-weight: 400;
            }
            .pdfsign-section img{
                height: auto;
                display: block;
                border: 0;
                max-width: 140px;
                width: 100%;
            }
            .pdf-footer ul li{
                font-size: 14px;
                color: #fff;
                /*vertical-align: middle;*/
                /*letter-spacing: undefined;*/
                /*text-align: left;*/
                /*margin-bottom: 40px;*/
                position: relative;
                padding-left: 30px;
            }
            .pdf-footer ul li img {
                position: absolute;
                left: 0;
                top: 2px;
            }
            .pdfcontent-section p span {
                font-weight: bold;
            }
            .pdfcontent-section .name{
                margin: 0;
                color: #000;
                font-size: 22px;
                font-weight: 700;
                letter-spacing: normal;
            }
            .pdfcontent h6 {
                font-weight: 800;
                font-size: 22px;
                color: #000;
            }
            .pdf-header .logo{
                max-width: 265px;
                width: 100%;
                padding-right: 40px;
                margin-top: 10px;
            }
            .left .top-particle{
                width: 50%;
            }
            .right .bottom-particle{
                width: 50%;
            }
            .pdf-footer ul {
                background: red;
                border-top-right-radius: 40px;
                padding-left:3rem;
            }
            .custom-page-startr{
                padding-top:130px;
                padding-bottom:140px;
            }
            @media print {
                   body {
                    font-family: 'Montserrat', sans-serif !important;
                }
                .pdfcontent {
                background: url('admin/letters/middle-icon.png');
                background-repeat: no-repeat;
                background-size: contain;
                background-position: center;
            }
            }
    	</style>
    </head>

        <body>
        <header class="pdf-header">
            <div class="row mt-5">
                <div class="col-12" style="text-align:center;">
                    @if(!empty(settings()->black_logo))
                        <img src="{{ public_path('admin/assets/img/logo') }}/{{ settings()->black_logo }}" class="logo" />
                    @else
                        <img src="{{ asset('public/admin/default.png') }}" style="width:150px" class="logo" title="Company Black Logo Here..." alt="Default"/>
                    @endif
                </div>
            </div>
        </header>
        <div class="custom-page-start px-5">
            <section class="pdfcontent-section px-4">
                <div class="row mt-5">
                    <div class="col-12 mt-5">
                        <h6 class="date">Date: {{ $model->effective_date }}</h6>
                        <h4 class="name"><br/><br/><br/>Dear {{ $model->name }},</h4>
                        <div class="pdfcontent mt-3 position-relative">
                            <div class="bg">
                                <img src="{{ public_path('admin/letters/middle-icon.png') }}"  />
                            </div>
                            <p>Consequent to the review of your performance, we are pleased to promote you as {{ $model->employee_designation }} - {{ $model->employee_department }} and your monthly salary has been revised and a raise of Rs.{{ $model->raise_salary }}/= has been awarded which will make your new gross salary Rs.{{ $model->salary }}/= ({{ $model->employee_salary_in_words }}) effective from {{ $model->effective_date }}.@if(!empty($model->vehicle_name)) Additionally you are entitled for {{ $model->vehicle_cc }}cc company maintained car. @endif
                            </p>
                            <p><br/>
                                Your annual performance appraisal for the year {{ date('Y') }} is {{ $model->increased_percent }}%. All other terms and conditions of your employment remain unchanged. This raise is a recognition of your outstanding efforts. The company values your contribution and continuously looks for ways to reward dedicated and smart working team members like you. We appreciate your efforts and contribution to {{ appName() }} and expect that you will continue to take up the new challenges with the same enthusiasm and zeal in the future as well. We are fortunate to have someone like you in our team. Congratulations and best of luck in the future.<br/><br/><br/></p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="pdfsign-section px-4 mt-2">
                <div class="row">
                    <div class="col-12">
                        @if(!empty(settings()->admin_signature))
                            <img src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->admin_signature }}" alt="Signature" />
                        @endif
                        <h6 class="mt-2"> Sincerely, </h6>
                        <h6>{{ hrName() }}</h6>
                        <h6>Executive – HR</h6>
                        <h6>{{ appName() }}</h6>
                    </div>
                </div>
            </section>
        </div>
        <footer class="pdf-footer ps-5">
            <div class="row">
                <div class="col-12">
                    <ul class="list-unstyled ps-5 pt-5 pb-3 pe-3">
                        @if(!empty(settings()->phone_number))
                            <li>
                                <img class="me-2" src="{{ asset('public/admin/letters/Call.png') }}" alt="Number Icon" />
                                {{ settings()->phone_number }}
                                <br/><br/>
                            </li>
                        @endif
                        @if(!empty(settings()->website_url))
                            <li>
                                <img class="me-2" src="{{ asset('public/admin/letters/Website.png') }}" alt="Website Icon" />
                                {{ settings()->website_url }}
                                <br/><br/>
                            </li>
                        @endif
                        @if(!empty(settings()->email))
                        <li>
                            <img class="me-2" src="{{ asset('public/admin/letters/Email.png') }}" alt="Email Icon" />
                            {{ settings()->email }}<br/><br/>
                        </li>
                        @endif
                        @if(!empty(settings()->address))
                            <li>
                                <img class="me-2" src="{{ asset('public/admin/letters/Address.png') }}" alt="Address Icon" />
                                {{ settings()->address }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </footer>
    </body>
</html>
