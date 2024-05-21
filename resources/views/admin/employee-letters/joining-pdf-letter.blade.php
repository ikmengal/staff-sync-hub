<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $model->title }}</title>
        <link rel="stylesheet" href="{{ public_path('admin/assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{ public_path('admin/assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800&display=swap');
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
                    @if(!empty(getCompanySettings($company)->black_logo))
                        <img src="{{ public_path('admin/assets/img/logo') }}/{{ getCompanySettings($company)->black_logo }}" class="logo" />
                    @else
                        <img src="{{ asset('public/admin/default.png') }}" style="width:150px" class="logo" title="Company Black Logo Here..." alt="Default"/>
                    @endif
                </div>
            </div>
        </header>
        <div class="custom-page-start px-5">
            <section class="pdfcontent-section px-4" style="width:80%; margin:0% auto; padding:0%">
                
                <div class="row mt-5">
                    <div class="col-12 mt-5">
                        <h6 class="date">Date: {{ $model->effective_date }}</h6>
                        <h4 class="name">Dear {{ $model->name }},</h4>
                        <div class="pdfcontent mt-3 position-relative">
                            <div class="bg">
                                <img src="{{ public_path('admin/letters/middle-icon.png') }}"  />
                            </div>
                            <p>Further to your interview, we are pleased to offer you employment at <span>{{ getCompanySettings($company)->name }}</span> as a <span>{{ $model->designation }} – {{ $model->department }}</span>. We believe that your skills, experience and enthusiasm will make you a valuable member of our team.</p>
                            <p>The terms and conditions of your employment are as follows: -</p>
                            <h6 style="font-family:'Montserrat', sans-serif; margin:0%;">Compensation and Benefits:</h6>
                            <p>
                                Your starting salary will be <span>{{ $model->salary }}/= ({{ ucwords($model->salary_in_words) }} Only)</span> per month. In addition to this, you will be entitled to 24 paid leaves annually (after confirmation) and EOBI. Any other regular employee benefits will be announced by the company as and when applicable.
                                @if($model->is_vehicle)
                                    Additionally you are entitled for {{ $model->vehicle_cc }} company maintained car.
                                @endif
                            </p>
                            <h6 style="margin: 0%">Probation and Notice Period:</h6>
                            <p>After appointment, your probationary period shall be of 3 months, during which the appointment may be terminated by either you or the company on one week's notice period. After the probationary period, one month's notice period shall be required from both parties, and payment made in lieu thereof.</p>
                            <h6 style="margin:0%;">Reporting Relationship:</h6>
                            <p>You shall report to <span>{{ $model->reporting_name }} – {{ $model->reporting_designation }} ({{ $model->reporting_department }})</span> or any other person designated by him.</p>
                            <h6 style="margin:0%;">Confidentiality:</h6>
                            <p>You shall not disclose any information about our business to anybody except to the person(s) having such authority to discuss such information.</p>
                            <h6 style="margin:0%;">Validity of This Offer:</h6>
                            <p>This employment offer is valid till <span>{{ $model->validity_date }}</span>. The validity of this letter is dependent on the originality of the documents that you will be submitting.</p>
                            <p>We are excited to have you join our team and look forward to working with you. Furthermore, it is important that you submit all the required documents mentioned in the list provided along with your offer letter in a timely manner to ensure that your appointment letter and payroll processing are not delayed. If you have any questions, please do not hesitate to contact us.</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="pdfsign-section px-4 mt-2" style="width:80%; margin:0% auto; padding:0%">
                <div class="row">
                    <div class="col-12">
                        @if(!empty(getCompanySettings($company)->admin_signature))
                            <img src="{{ asset('public/admin/assets/img/logo') }}/{{ getCompanySettings($company)->admin_signature }}" alt="Signature" />
                        @endif
                        <h6 style="margin: 1% 0%"> Sincerely, </h6>
                        <h6 style="margin: 1% 0%">{{ hrName() }}</h6>
                        <h6 style="margin: 1% 0%">Executive – HR</h6>
                        <h6 style="margin: 1% 0%">{{ getCompanySettings($company)->name }}</h6>
                    </div>
                </div>
            </section>
        </div>
        <footer class="pdf-footer ps-5">
            <div class="row">
                <div class="col-12">
                    <ul class="list-unstyled ps-5 pt-5 pb-3 pe-3" style="list-style: none; padding:5%">
                        @if(!empty(getCompanySettings($company)->phone_number))
                            <li>
                                <img class="me-2" src="{{ asset('public/admin/letters/Call.png') }}" alt="Number Icon" />
                                {{ getCompanySettings($company)->phone_number }}
                                <br/><br/>
                            </li>
                        @endif
                        @if(!empty(getCompanySettings($company)->website_url))
                            <li>
                                <img class="me-2" src="{{ asset('public/admin/letters/Website.png') }}" alt="Website Icon" />
                                {{ getCompanySettings($company)->website_url }}
                                <br/><br/>
                            </li>
                        @endif
                        @if(!empty(getCompanySettings($company)->email))
                        <li>
                            <img class="me-2" src="{{ asset('public/admin/letters/Email.png') }}" alt="Email Icon" />
                            {{ getCompanySettings($company)->email }}<br/><br/>
                        </li>
                        @endif
                        @if(!empty(getCompanySettings($company)->address))
                            <li>
                                <img class="me-2" src="{{ asset('public/admin/letters/Address.png') }}" alt="Address Icon" />
                                {{ getCompanySettings($company)->address }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </footer>
    </body>
</html>
