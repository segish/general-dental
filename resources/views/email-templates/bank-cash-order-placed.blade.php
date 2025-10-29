<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Order Placed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
    /**
         * Google webfonts. Recommended to include the .woff version for cross-client compatibility.
         */
    @media screen {
        @font-face {
            font-family: 'Source Sans Pro';
            font-style: normal;
            font-weight: 400;
            src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format('woff');
        }

        @font-face {
            font-family: 'Source Sans Pro';
            font-style: normal;
            font-weight: 700;
            src: local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format('woff');
        }
    }

    /**
         * Avoid browser level font resizing.
         * 1. Windows Mobile
         * 2. iOS / OSX
         */
    body,
    table,
    td,
    a {
        -ms-text-size-adjust: 100%;
        /* 1 */
        -webkit-text-size-adjust: 100%;
        /* 2 */
    }

    /**
         * Remove extra space added to tables and cells in Outlook.
         */
    table,
    td {
        mso-table-rspace: 0pt;
        mso-table-lspace: 0pt;
    }

    /**
         * Better fluid images in Internet Explorer.
         */
    img {
        -ms-interpolation-mode: bicubic;
    }

    /**
         * Remove blue links for iOS devices.
         */
    a[x-apple-data-detectors] {
        font-family: inherit !important;
        font-size: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
        color: inherit !important;
        text-decoration: none !important;
    }

    /**
         * Fix centering issues in Android 4.4.
         */
    div[style*="margin: 16px 0;"] {
        margin: 0 !important;
    }

    body {
        width: 100% !important;
        height: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    /**
         * Collapse table borders to avoid space between cells.
         */
    table {
        border-collapse: collapse !important;
    }

    a {
        color: #1a82e2;
    }

    img {
        height: auto;
        line-height: 100%;
        text-decoration: none;
        border: 0;
        outline: none;
    }
    .card {
    max-width: 600px;
    margin: 0 auto;
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    /* Add more styles as needed */
}

.card-header h4 {
    color: #333;
    /* Add more styles as needed */
}

    </style>
</head>

<body style="background-color: #e9ecef;">
    <!-- end preheader -->
    <div class="card" style="max-width: 600px; margin: 0 auto; background-color: #f8f9fa; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <div class="card-header">
            <h4 style="color: #333;">{{ \App\CentralLogics\translate('Hello ' . $customer->f_name ." ".$customer->l_name ) }},</h4>
            <p>{{ \App\CentralLogics\translate('Thank you for choosing the bank payment option to make your payment. Below are the bank details you will need to complete the payment:') }}
            </p>
            <p>{{ \App\CentralLogics\translate('Account Holder: AGTA PLC') }} </p>

            <p>{{ \App\CentralLogics\translate('Amount: ETB '.$order->order_amount) }}</p>
            <br>
            <p>{{ \App\CentralLogics\translate('Bank Name: CBE Gofa') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number: 1000007595313') }}</p>
            <br>
            <p>{{ \App\CentralLogics\translate('Bank Name: CBE Garaduba Branch') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number: 10000036737224') }}</p>
            <br>
            <p>{{ \App\CentralLogics\translate('Bank Name: Wegagen') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number 1: 0099395530101') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number 2: 0099395510104') }}</p>
            <br>
            <p>{{ \App\CentralLogics\translate('Bank Name: Cooperative') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number: 1000000023438') }}</p>
            <br>
            <p>{{ \App\CentralLogics\translate('Bank Name: Buna') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number: 1779601000159') }}</p>
            <br>
            <p>{{ \App\CentralLogics\translate('Bank Name: Dashen') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number: 0187001119019') }}</p>
            <br>
            <p>{{ \App\CentralLogics\translate('Bank Name: Awash') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number 1: 01320047590001') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number 2: 1304047590000') }}</p>
            <br>
            <p>{{ \App\CentralLogics\translate('Bank Name: Oromia international') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number: 1119354000001') }}</p>
            <br>
            <p>{{ \App\CentralLogics\translate('Bank Name: Lion') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number 1: 00100006297') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number 2: 00711193329') }}</p>
            <br>
            <p>{{ \App\CentralLogics\translate('Bank Name: Abisiniya') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number 1: 35720588') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number 2: 35679766') }}</p>
            <br>
            <p>{{ \App\CentralLogics\translate('Bank Name: Birhan Bank') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number: 2500040004389') }}</p>
            <br>
            <p>{{ \App\CentralLogics\translate('Bank Name: Oromia international bank Derartu') }}</p>
            <p>{{ \App\CentralLogics\translate('Account Number: 1119354000003') }}</p>
            <br>
            <p> {{ \App\CentralLogics\translate('Please make sure to include the reference or invoice number when sending email after you completed payment:') }}
            </p>

            <p>{{ \App\CentralLogics\translate('Once you have initiated the bank transfer, please contact our information desk on 0911223344 and allow up to 2 working days for the payment to be processed and verified.') }}
            </p>

            <p>{{ \App\CentralLogics\translate('As soon as we receive confirmation of your payment, we will update your account and send you a confirmation email. If you have any questions or need further assistance, please don’t hesitate to contact our support team at info@agtaa.com. Thank you for choosing our services. Best regards,') }}
            </p>

        </div>
    </div>
    @extends('email-templates.template_footer')
</body>

</html>