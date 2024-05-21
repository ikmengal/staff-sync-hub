<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
    <head>
        <title>{{ $model->title }}</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <link href="https://fonts.googleapis.com/css?family=Noto+Serif" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css2?family=Inter&family=Work+Sans:wght@700&display=swap" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700;900&display=swap" rel="stylesheet" type="text/css"/>
        @if(!empty(getCompanySettings($company)->favicon))
            <link rel="icon" type="image/x-icon" href="{{ asset('public/admin') }}/assets/img/favicon/{{ getCompanySettings($company)->favicon }}" />
        @else
            <link rel="icon" type="image/x-icon" href="{{ asset('public/admin') }}/assets/img/favicon/favicon.ico" />
        @endif
        <style>
            @media print {
                * {
                    -webkit-print-color-adjust: exact !important; /*Chrome, Safari */
                    color-adjust: exact !important;  /*Firefox*/
                }
                body {
                    margin: 0;
                    padding: 0;
                    position:relative;
                }
                
                body:after{
                    position:absolute;
                    content:'';
                    top:0;
                    left:50%;
                    transform:translateX(-50%);
                    height:3px;
                    width:70%;
                    background:red;
                }
            
                .pdf-header {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 100px; /* Set header height */
                }
            
                .custom-page-start{
                    margin-top: 60px; /* Adjust based on header height */
                    margin-bottom: 40px; /* Adjust based on footer height */
                }
            
                .pdf-footer {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    height: 100px; /* Set footer height */
                }
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
                content: '';
                background: url(public/admin/letters/middle-icon.png);
                background-repeat: no-repeat;
                background-size: contain;
                background-position: center;
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
                opacity: .2;
            }
            .header-left{
                width:50%;
                float:left;
            }
            .header-right {
                width: 50%;
                float: right;
                text-align: right;
                padding-right: 40px;
            }
            .pdfcontent-section.pdfcontent-section .date {
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
            .footer-left{
                width:50%;
                float:left;
            }
            .footer-right{
                width:50%;
                float:right;
                display: flex;
                justify-content: end;
            }
            .pdf-footer ul li{
                font-size: 14px;
                color: #fff;
                vertical-align: middle;
                letter-spacing: undefined;
                text-align: left;
                margin-bottom: 40px;
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
            .pdfcontent-section .name {
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
                margin-top: 10px;
            }
            .pdf-header .top-particle{
                width: 70%;
            }
            .pdf-footer .bottom-particle{
                width: 100%;
            }
            .pdf-footer ul {
                background: red;
                border-top-right-radius: 40px;
            }
    	</style>
    </head>
        <body>
        <header class="pdf-header">
            <div class="row mt-5">
                <div class="col-12 text-center">
                    @if(!empty(getCompanySettings($company)->black_logo))
                        <img src="{{ asset('public/admin/assets/img/logo') }}/{{ getCompanySettings($company)->black_logo }}" alt="{{ getCompanySettings($company)->name }}" class="logo img-fluid" />
                    @else
                        <img src="{{ asset('public/admin/default.png') }}" style="width:150px" class="logo img-fluid" title="Company Black Logo Here..." alt="Default"/>
                    @endif
                </div>
            </div>
        </header>
        <div class="custom-page-start px-5 mt-5">
            <section class="pdfcontent-section px-4">
                <div class="row">
                    <div class="col-12 mt-5">
                        <h6 class="date">Date: {{ $model->effective_date }}</h6>
                        <h4 class="name">Dear {{ $model->name }},</h4>
                        <div class="pdfcontent mt-3 position-relative">
                            <div class="bg"></div>
                            <p>Further to your interview, we are pleased to offer you employment at <span>{{ $company }}</span> as a <span>{{ $model->designation }} – {{ $model->department }}</span>. We believe that your skills, experience and enthusiasm will make you a valuable member of our team.</p>
                            <p>The terms and conditions of your employment are as follows: -</p>
                            <h6 class="mt-2">Compensation and Benefits:</h5>
                            <p>
                                Your starting salary will be <span>{{ $model->salary }}/= ({{ ucwords($model->salary_in_words) }} Only)</span> per month. In addition to this, you will be entitled to 24 paid leaves annually (after confirmation) and EOBI. Any other regular employee benefits will be announced by the company as and when applicable.
                                @if($model->is_vehicle)
                                    Additionally you are entitled for {{ $model->vehicle_cc }} company maintained car.
                                @endif
                            </p>
                            <h6 class="mt-3">Probation and Notice Period:</h5>
                            <p>After appointment, your probationary period shall be of 3 months, during which the appointment may be terminated by either you or the company on one week's notice period. After the probationary period, one month's notice period shall be required from both parties, and payment made in lieu thereof.</p>
                            <h6 class="mt-3">Reporting Relationship:</h5>
                            <p>You shall report to <span>{{ $model->reporting_name }} – {{ $model->reporting_designation }} ({{ $model->reporting_department }})</span> or any other person designated by him.</p>
                            <h6 class="mt-3">Confidentiality:</h5>
                            <p>You shall not disclose any information about our business to anybody except to the person(s) having such authority to discuss such information.</p>
                            <h6 class="mt-3">Validity of This Offer:</h5>
                            <p>This employment offer is valid till <span>{{ $model->validity_date }}</span>. The validity of this letter is dependent on the originality of the documents that you will be submitting.</p>
                            <p>We are excited to have you join our team and look forward to working with you. Furthermore, it is important that you submit all the required documents mentioned in the list provided along with your offer letter in a timely manner to ensure that your appointment letter and payroll processing are not delayed. If you have any questions, please do not hesitate to contact us.</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="pdfsign-section px-4 mt-2">
                <div class="row">
                    <div class="col-12">
                        @if(!empty(getCompanySettings($company)->admin_signature))
                            <img src="{{ asset('public/admin/assets/img/logo') }}/{{ getCompanySettings($company)->admin_signature }}" alt="Signature" />
                        @endif
                        <h6 class="mt-2"> Sincerely, </h6>
                        <h6>{{ hrName() }}</h6>
                        <h6>Executive – HR</h6>
                        <h6>{{ $company }}</h6>
                    </div>
                </div>
            </section>
        </div>
        <footer class="mt-5 pdf-footer">
            <div class="row">
                <div class="col-5">
                    <ul class="list-unstyled ps-5 pt-5 pb-3 pe-3">
                        @if(!empty(getCompanySettings($company)->phone_number))
                        <li class="mb-1">
                            <img class="me-2" src="{{ asset('public/admin/letters/Call.png') }}" class="img-fluid" alt="Number Icon" />
                            {{ getCompanySettings($company)->phone_number }}
                        </li>
                        @endif
                        @if(!empty(getCompanySettings($company)->website_url))
                        <li class="mb-1">
                            <img class="me-2" src="{{ asset('public/admin/letters/Website.png') }}" class="img-fluid" alt="Website Icon" />
                            {{ getCompanySettings($company)->website_url }}
                        </li>
                        @endif
                        @if(!empty(getCompanySettings($company)->email))
                        <li class="mb-1">
                            <img class="me-2" src="{{ asset('public/admin/letters/Email.png') }}" class="img-fluid" alt="Email Icon" />
                            {{ getCompanySettings($company)->email }}
                        </li>
                        @endif
                        @if(!empty(getCompanySettings($company)->address))
                        <li class="mb-1">
                            <img class="me-2" src="{{ asset('public/admin/letters/Address.png') }}" class="img-fluid" alt="Address Icon" />
                            {{ getCompanySettings($company)->address }}
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </footer>
    </body>
</html>