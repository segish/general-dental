<!DOCTYPE html>
<html lang="am">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Consent Form PDF</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Ethiopic:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Fonts */
        @font-face {
            font-family: 'Noto Sans Ethiopic';
            src: url('{{ public_path('fonts/NotoSansEthiopic-VariableFont_wdth,wght.ttf') }}') format('truetype');
        }

        /* Basic Layout */
        body {
            font-family: 'Noto Sans Ethiopic', Arial, sans-serif;
            margin: 2px;
            color: #000;
        }

        h1, h2, h3, h4 {
            text-align: center;
            margin: 5px 0;
        }

        /* Contact Info */
        .contact-info p {
            text-align: center;
            font-weight: bold;
            margin: 2px 0;
        }

        /* Divider */
        .divider {
            border-top: 2px solid #000;
            margin: 5px 0;
        }

        /* Form Fields */
        .field-label {
            display: inline-block;
            margin-right: 10px;
        }

        .underline {
            display: inline-block;
            width: auto;
            border-bottom: 2px solid #000;
            padding: 2px 0;
            margin-right: 10px;
        }

        .custom-underline {
            display: inline-block;
            width: 150px;
            border-bottom: 2px solid #000;
            padding: 2px 0;
            margin-right: 10px;
        }

        .custom-underline-large {
            display: inline-block;
            width: 200px;
            border-bottom: 2px solid #000;
            padding: 2px 0;
            margin-right: 10px;
        }

        /* Checkbox */
        .checkbox {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 1px solid #000;
            vertical-align: middle;
            margin-right: 5px;
        }

        /* Paragraph Style */
        .paragraph {
            margin-bottom: 0.5rem;
            line-height: 0.9;
        }
    </style>
</head>

<body>
    <div>
        <h1 style="margin-top: 1px;">FINOT MEDIUM DENTAL CLINIC</h1>
    </div>

    <div class="contact-info">
        <p>Tel. 011 259 1404</p>
        <p>አድራሻ፡- Ayer tena Kolfe Sub city</p>
    </div>

    <div class="divider"></div>

    <div style="text-decoration: underline;">
        <h3>ህሙማን ኦፕራሲዮን ለመሆን ጥርስ ለመነቀል ብሬስ ለማሰራት እና ሰመመን ለመውሰድ</h3>
        <h3>ስምምነት እና ፈቃድ መስጫ ቅፅ</h3>
    </div>

    <div class="paragraph">
        <span class="field-label">እኔ</span>
        <span class="underline">{{ $consentForm->patient->full_name }}</span>
        <span class="field-label">ዶ/ር</span>
        <span class="underline">{{ $consentForm->doctor->admin->f_name }} {{ $consentForm->doctor->admin->l_name }}</span>
        <span>እንደሚያስፈልገኝ ተገልፆልኝ ተቀብያለሁ፡፡</span>
    </div>

    <div class="paragraph">
        ለዚህም አስፈላጊ ከሆነ የሰመመን መድሃኒት ለመውሰድ ከዚህ በታች የተገለጸውን ነጥብ ላይ ግልፃ ተደርጎልኝ ተቀብያለሁ፡፡
    </div>

    <div class="paragraph">
        <p>1. ምን አይነት ሰመመን እንደምወስድ ምን አይነት ህክምና እንደሚሰራልኝ ጥቅሙና ሊያስከት የሚችለው ጉዳት ሌላ የሕከምና ምርጫ መኖር ወይም አለመኖር እንዲሁም ይህንን ህክምና ባልወስድ ሊመጣ የሚችለው ውጤት ተብራርቶልኝ የጠቅሁዋቸውም ጥያቄዎች ተመልሰውልኝ፤</p>
    </div>

    <div class="paragraph">
        <p>2. የሰመመን መደሃኒት ተሰጥቶ ኦፕራሲዮን ሲደረግ የሚያመጡት ያልተጠበቁ ችግሮች ሊኖሩ እንደሚችሉ ከዚህም መሀል መድማት ኢንፌክሽን የልብ ችግር ወይም የባሰ ችግር አንዳንዴም እስከ ህይወት ህልፈት ሊከሰት እንደሚችል ተነግሮኝ</p>
    </div>

    <div class="paragraph">
        <p>3. ህክምናው ሲደረግ ያልተጠበቁ ችግሮች ቢገኙ ሀኪሙ በሙያው አስፈላጊውን እርምጃ እንዲወስድ ሙሉ በሙሉ መፍቀዱን</p>
    </div>

    <div class="paragraph">
        <p>4. የሚሰጠኝ ህከምናው ምን ያህል ውጤታማ ሊሆን እንደሚችል ተገልጾልኛል፡፡</p>
    </div>

    <div class="paragraph">
        <p>ከላይ ከተራ ቁጥር 1-3 የተጠቀሱት በሚገባ አንብቤና ተነቦልኝ ተረድቻለሁ ለዚህም መስማማቴንና መፍቀዴን በፊርማዬ እይጋግጣለሁ፡፡</p>
    </div>

    <div class="paragraph" style="margin-top: 10px;">
        <span class="field-label">የፈቃጅ/ህፃን ከሆነ የወላጅ ወይም የአሳዳጊ /ስምና ፊርማ:</span>
        <span class="custom-underline-large"></span>
    </div>

    <div class="paragraph" style="margin-top: 10px;">
        <span class="field-label">አድራሻ</span>
        <span class="custom-underline"></span>
        <span class="field-label">ስልከ ቁጥር</span>
        <span class="custom-underline"></span>
        <span class="field-label">ቀን</span>
        <span class="custom-underline"></span>
    </div>

    <div class="paragraph" style="margin-top: 10px;">
        <span class="field-label">የምስከሮች ስምና ፊርማ 1.</span>
        <span class="custom-underline"></span>
        <span class="field-label">1</span>
        <span class="custom-underline"></span>
    </div>

    <div class="paragraph" style="margin-top: 10px;">
        <span class="field-label">የዝምድና ሁኔታ 1.</span>
        <span class="custom-underline"></span>
        <span class="field-label">2</span>
        <span class="custom-underline"></span>
    </div>

    <div class="paragraph" style="margin-top: 10px;">
        <span style="text-decoration: underline;">ማሳሰቢያ</span>
        <span>፡ ይህን ቅፅ ያለሞላው ግለሰብ ቅፁ በትከክል መፈረሙን ማረጋገጥ አለበት፡፡</span>
    </div>

    <div class="paragraph" style="margin-top: 10px;">
        <span class="field-label">ቅዑን ያስሞላው ሰው ከምና ፈርማ</span>
        <span class="custom-underline"></span>
        <span class="field-label">ቀን</span>
        <span class="custom-underline"></span>
    </div>
</body>

</html>
