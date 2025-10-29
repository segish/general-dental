<!DOCTYPE html>
<html>

<head>
    <title>በፍላጎት ጽንስን ለማቋረጥ (MA) በሚፈለጉ ደንበኞች የሚሞላ መተማመኛ ቅጽ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Ethiopic:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Noto Sans Ethiopic';
            src: url('{{ public_path('fonts/NotoSansEthiopic-VariableFont_wdth,wght.ttf') }}') format('truetype');
        }

        @page {
            margin: 0;
        }

        body {
            font-family: 'Noto Sans Ethiopic', Arial, sans-serif;
            font-size: 14px;
            margin-top: 150px;
            margin-left: 40px;
            margin-right: 40px;
            margin-bottom: 50px;
        }

        ol {
            padding-left: 20px;
        }

        ol li {
            margin-bottom: 12px;
        }

        .signature-section {
            margin-top: 40px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 25px;
        }

        .underline-strong {
            border-bottom: 2px solid black;
            padding: 2px 10px;
            display: inline-block;
            min-width: 150px;
        }

        .line {
            overflow: hidden;
            /* ensures floated items don't break layout */
            margin-bottom: 10px;
        }

        .right-align {
            float: right;
        }

        .long-line {
            display: inline-block;
            width: 50%;
            padding-left: 20px;
            border-bottom: 2px solid black;
            margin-left: 5px;
        }

        .short-line {
            display: inline-block;
            width: 50%;
            padding-left: 5px;
            border-bottom: 2px solid black;
        }

        .flex-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    @include('admin-views.pdf-components.header')
    @include('admin-views.pdf-components.watermark-top')
    @include('admin-views.pdf-components.digital_stamp')

    <div class="title">
        በፍላጎት ጽንስን ለማቋረጥ (MA) በሚፈለጉ ደንበኞች የሚሞላ መተማመኛ ቅጽ
    </div>

    <div class="flex-line">
        <span>እኔ</span>
        <div class="long-line">{{ $medicalDocument->visit->patient->full_name }}</div>
        <span class="right-align">የካርድ ቁጥር<span class="short-line">{{ $medicalDocument->visit->code }}</span></span>
    </div>

    <p>
        ከእርግዝና ጋር በተያያዘ ምክንያት ስለጤናዬ ሁኔታ ከህክምና ባለሙያ ጋር ምክክር ከአደረግሁ በኋላ በመድሃኒት (ኪኒን) አማካኝነት ጽንሱ እንዲቋረጥ ፈቅጃለሁ" ስለተለያዩ የጽንስ
        ማቋረጥ አማራጮችና ጽንሱ በሚቋረጥበት ጊዜ ሊከሰቱ ስለሚችሉ ያልተጠበቁ ችግሮች በተመለከተም በቂ ምክርና መረጃ ተሰጥቶኛል። ከነዚህም ውስጥ፦
    </p>
    <ol>
        <li> በመድኃኒት (ኪኒን) ጽንስ ማቋረጥ ምን እንደሆነና ኪኒኖቹ እንዴት እንደሚወሰዱ ተረድቻለሁ::</li>

        <li> ጽንሱን ለማቋረጥ ከልቤ የወሰንኩ ቢሆንም የጽንስ ማቋረጡ ከመካሄዱ በፊት በማንኛውም ሰዓት ጽንስ የማቋረጥ ሀሳቤን መቀየር እንደምችል፡</li>

        <li>የጽንስ ማቋረጥ ሂደት ጥቅምም ጉዳትም አለው። ያልተጠበቁ ችግሮች የመከሰታቸው ሁኔታ እጅግ አናሳ ቢሆንም እነዚህ ችግሮች ከተከሰቱ ማመርቀዝ፡ ከመጠን ያለፈ ደም መፍሰስ፡
            ማቅለሽለሽ፡ ማስመለስና ማንቀጥቀጥ ሊኖር እንደሚችል፡</li>

        <li>መድሃኒቱ ሲወሰድ የሚያጋጥሙ የተለመዱ ምልክቶች ማለትም አንደቁርጠትና ደም መፍሰስ ማቅለሽለሽ፣ ማስመለስ፡ ተቅማጥ፣ ትኩሳትና ማንቀጥቀጥ ሊኖር እንደሚችል፡</li>

        <li> የተወሰደው ኪኒን ጽንሱን ማቋረጥ ካልቻለ ወይም መድኃኒቱን በትክክል ሳልወስድ ቀርቼ ጽንሱ ባይቴርሞ በወሰድኩት መድሃኒት ምክንያት ጽንሱ እክል ሲያጋጥመው ስለሚችል
            እርግዝናውን በስሪንጅ በመጠቀም በመጥረግ (VA) ማቋረጥ እንደሚገባ፡</li>

        <li>በሚሰጡኝ ቀጠሮዎች መሰረት ወደ ጤና ድርጅቱ መመለስ እንዳለብኝና ችግር ባጋጠመኝ ሰዓት እርዳታና ምክር የማገኝበት ስልክ ቁጥር መኖሩንና በአቅራቢያዬ ወደሚገኝ የጤና ተቋም
            መሔድ እንዳለብኝ፡</li>

        <li> ከብልት ደም መፍሰሱ ብቻ ጽንስ መቋረጡን የሚያሳይ አለመሆኑን የተረዳሁ ሲሆን እርግዝና . መቀጠሉን ወይም የጽንሱ ቅሪት አለመኖሩን ለማረጋገጥ በተሰጠኝ ቀጠሮ መገኘት
            እንዳለብኝ </li>

        <li>ሁለት ወር ከአንድ ሳምንት (9 ሳምንት) በታች የሆነን ጽንስ ለማቋረጥ ከመድኃኒት በተጨማሪ በስሪንጅ በመጠቀም በመጥረግ (V) ለማቋረጥ አማራጭ እንዳለ ተረድቻለሁ</li>
    </ol>

    <p>
        ስለሆነም በጽንስ ማቋረጥ ሂደት ውስጥ ያልተጠበቁ ችግሮች ቢከሰቱ ችግሮቹን ለማቆምም ሆነ በህይወቴ ላይ የሚደርሰውን አደጋ ለመታደግ የህክምና ባለሙያዎች አስፈላጊ የሆነውን ሁሉ
        እንዲያደርጉልኝ ፈቅጃለሁ።
    </p>

    <p>
        ከላይ ለህክምና ባለሙያ የሰጠሁት መረጃ ትክክለኛ መሆኑን በፊርማዬ አረጋግጣለሁ።
    </p>

    <div class="signature-section">
        <div class="line">
            <span>የተጠቃሚ ስም እና ፊርማ <span
                    class="underline-strong">{{ $medicalDocument->visit->patient->full_name }}</span></span>
            <span class="right-align">ቀን <span
                    class="underline-strong">{{ \Carbon\Carbon::parse($medicalDocument->created_at)->format('Y-m-d') }}</span></span>
        </div>
        <div class="line">
            <span>የአገልጋይ ስም እና ፊርማ <span
                    class="underline-strong">{{ $medicalDocument->visit->doctor->fullname }}</span></span>
            <span class="right-align">ቀን <span
                    class="underline-strong">{{ \Carbon\Carbon::parse($medicalDocument->created_at)->format('Y-m-d') }}</span></span>
        </div>
    </div>

    @include('admin-views.pdf-components.footer')
</body>

</html>
