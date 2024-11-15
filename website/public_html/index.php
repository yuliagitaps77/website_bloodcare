<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="landing page.">
    <meta name="author" content="Devcrud">

    
    <link rel="stylesheet" href="assets/vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/owl-carousel/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/vendors/owl-carousel/css/owl.theme.default.css">
	<link rel="stylesheet" href="assets/css/ollie.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Sansita+Swashed:wght@300..900&display=swap" rel="stylesheet">



</head>
<style>
.main-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 75vh;
    background-color: #DF3232;
    color: white;
}

.content-wrapper {
    max-width: 800px;
    text-align: center;
}

.heading {
    font-size: 32px; /* Increased font size */
    font-weight: bold;
    margin-bottom: 40px;
}
/* Container for both columns */
.requirements-container {
    display: flex;
    justify-content: space-between;
    padding: 20px;
    flex-wrap: wrap; /* Allow wrapping for smaller screens */
}

/* Left and Right Columns */
.requirements-column {
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 35%; /* Initial width for larger screens */
}

/* Common styling for requirement items */
.requirement-item {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 20px;
    font-weight: bold;
    color: white;
    max-width: 300px;
}

/* Left Column (Text, Icon) */
.requirements-column.left .requirement-item {
    flex-direction: row; /* Text on the left, Icon on the right */
    text-align: left;
    justify-content: start;
}

/* Right Column (Icon, Text) */
.requirements-column.right .requirement-item {
    flex-direction: row; /* Icon on the left, Text on the right */
    text-align: left;
    justify-content: start;
}

/* Fixed width for text to align items across columns */
.requirement-item span {
    flex: 1;
    min-width: 180px; /* Adjust as needed for alignment */
}

/* Icon styling */
.requirement-icon {
    width: 70px;
    height: 70px;
    background-color: white;
    border-radius: 50%;
    flex-shrink: 0; /* Prevent icon from shrinking */
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .requirements-container {
        flex-direction: column;
        align-items: center;
    }
    
    .requirements-column {
        width: 100%; /* Full width for each column on smaller screens */
        align-items: center;
    }

    .requirement-item {
        max-width: 100%; /* Allow items to expand to full width */
        justify-content: center; /* Center-align items on smaller screens */
        text-align: center; /* Center text */
    }

    /* Adjust icon size and spacing for smaller screens */
    .requirement-icon {
        width: 60px;
        height: 60px;
    }

    .requirement-item span {
        min-width: 0; /* Remove fixed width for better flexibility */
    }
}


</style>
<body data-spy="scroll" data-target=".navbar" data-offset="40" id="home">

    <nav id="scrollspy" class="navbar navbar-light bg-light navbar-expand-lg fixed-top" data-spy="affix" data-offset-top="20">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="assets/imgs/logo pmi bloodcare.png" alt="" class="brand-img"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">ABOUT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#portfolio">SERVICE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testmonial">CONTACT</a>
                    </li>

                    <li class="nav-item ml-0 ml-lg-4">
                    <a class="nav-link btn btn-primary" href="auth/Masuk.html">Sign In</a>
                    </li>
                    <li class="nav-item ml-0 ml-lg-4">
                    <a class="nav-link btn btn-secondary" href="auth/daftar.html">Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <header id="home" class="header">
        <div class="overlay"></div>

        <div id="header-carousel" class="carousel slide carousel-fade" data-ride="carousel">  
            <div class="container">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="carousel-caption d-none d-md-block">
                            <h1 class="carousel-title">Donate your blood & Save the live <br> 
                                </h1>
                            <p class="carousel-description">Donating blood is a simple yet powerful way to save lives. Each donation can restore hope and give a second chance to those in need. Your blood can be the difference in emergency situations, surgeries, or for those battling illness. Be a hero—donate your blood, save lives, and let your kindness make a lasting impact<br> 
                            </p>
                        
                        </div>
                    </div>
    
                </div>
            </div>        
        </div>

        <div class="infos container mb-4 mb-md-2">
            <div class="title">
              
            </div>
            <div class="socials text-right">
                <div class="row justify-content-between">
                    <div class="col">
                    </div>
                    <div class="col">
                       
                    </div>
                </div>
            </div>
        </div>
        
    </header>
    <section class="section" id="about">
     
        <div class="container mb-1">
         
        </div>
    </section>
    <section class="section" id="about">
     
        <div class="container mb-3">
         
            <div class="blog-wrapper">
                <div class="img-wrapper">
                    <img src="assets/imgs/logo pmi aja.png" alt="logo pmi">
                  
                </div>
                <div class="txt-wrapper">
                    <h4 class="blog-title">Sejarah PMI </h4>
                    <p>Palang Merah di Indonesia bermula dari Nederlands Rode Kruis Afdeling Indie (Nerkai) yang didirikan Pemerintah Belanda pada 21 Oktober 1873, namun dibubarkan saat pendudukan Jepang. Upaya mendirikan Palang Merah Indonesia dimulai tahun 1932 oleh Dr. RCL Senduk dan Dr. Bahder Djohan, meski mendapat penolakan dari Nerkai dan tentara Jepang.
                        Setelah proklamasi kemerdekaan, pada 3 September 1945, Presiden Soekarno memerintahkan pembentukan Palang Merah Nasional. Dr. Buntaran, Menteri Kesehatan saat itu, membentuk Panitia 5, dan pada 17 September 1945, Palang Merah Indonesia (PMI) resmi berdiri. PMI diakui secara internasional pada tahun 1950 dan disahkan melalui Keppres No.25 tahun 1959. Kini PMI memiliki jaringan luas di seluruh Indonesia dengan dukungan 
                        165 unit Transfusi Darah.</p>

                </div>
            </div>
        </div>
    </section>

    <style>

.blog-title {
    color: #DF3232;}

     .horizontal-list {
            display: flex;
            flex-wrap: wrap; /* Allows wrapping to next row on smaller screens */
            padding: 20px;
            gap: 10px; /* Spacing between items */
            justify-content: space-between; /* Spacing adjustments between items */
        }

        /* List Item Style */
        .list-item {
            flex: 1 1 calc(25% - 10px); /* Set each item to take 25% width minus gap */
            background-color: #ff5151;
            border-radius: 10px;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Image Style */
        .list-item img {
            width: 100px; /* Bigger image size */
            height: 100px;
            margin-bottom: 15px;
            border-radius: 50%;
            background-color: #ffffff;
            padding: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Text Style */
        .list-item h6 {
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            margin: 10px 0 0;
        }

        /* Responsive Adjustment */
        @media (max-width: 768px) {
            .list-item {
                flex: 1 1 calc(50% - 10px); /* On smaller screens, each item takes 50% width */
            }
        }

        @media (max-width: 480px) {
            .list-item {
                flex: 1 1 calc(100% - 10px); /* On very small screens, each item takes full width */
            }
        }
    </style>
    <section class="section-white" id="portfolio">
        <div class="container">
            <h3 class="section-title pb-4">Manfaat Donor Darah Bagi Tubuh</h3>
        </div>

        <div class="horizontal-list">
    <div class="list-item">
        <img src="assets/imgs/ic_1.svg" alt="gambar sel darah">
        <h6>Merangsang pembentukan sel darah baru</h6>
    </div>
    <div class="list-item">
        <img src="assets/imgs/ic_2.svg" alt="gambar jantung">
        <h6>Menjaga kesehatan jantung</h6>
    </div>
    <div class="list-item">
        <img src="assets/imgs/ic_3.svg" alt="gambar donor darah">
        <h6>Mendonorkan Darah 450ml Dapat Membakar 650cal</h6>
    </div>
    <div class="list-item">
        <img src="assets/imgs/ic_4.svg" alt="gambar penyakit">
        <h6>Mendeteksi Penyakit</h6>
    </div>
</div>
    </section>

    <section class="section-white" id="blog">
        <div class="section-white">
            <h3 class="section-title mb-5">KISAH INPIRATIF</h3>

            <div class="blog-wrapper">
                <div class="img-wrapper">
                    <img src="assets/imgs/kisah inspiratif 1.png" alt="kisah inspiratif 1">
                </div>
                <div class="txt-wrapper">
                    <h4 class="blog-title">Kisah Inspiratif Andreas Mangu Sudah 110 Kali Donor Darah Dimulai Sejak Tahun 1982</h4>
                    <p class="description-blog">
                        Lakukanlah kebaikan selagi kesempatan itu ada. Karena barangsiapa yang menabur maka dia akan menuai. Ungkapan ini sangat pantas diberikan pada Andreas Mangu, sosok pria yang kuat dan perkasa kelahiran 13 September 1954.
                        Pria yang berdomisili di Kelurahan Penfui ini adalah seorang pensiunan PT Angkasa Pura. Selama 35 tahun 6 bulan, seluruh hidupnya didedikasikan bagi salah satu BUMN pemerintah di bidang penerbangan ini.
                    </p>
                    <a class="nav-link btn btn-primary" href="components.html" style="width: 20%; text-align: center;">SELENGKAPNYA</a></div>
            </div>
           
<div class="blog-wrapper">
    <div class="img-wrapper">
        <img src="assets/imgs/kisah inspiratif 2.png" alt="kisah inspiratif 2">
    </div>
    <div class="txt-wrapper">
        <h4 class="blog-title">Cerita Inspiratif Edward Mandala, Pegawai Bank TLM Yang Aktif Donor Apheresis, Ternyata Pernah Alami Darah Kental</h4>
        <p class="description-blog">
        Sebuah cerita inspiratif terkait donor darah datang dari salah satu pegawai Bank TLM, Edward Mandala. Kepala Seksi Funding Bank TLM ini, bukan cuma aktif sebagai pendonor untuk donor darah, tetapi kini aktif juga sebagai pendonor apheresis.  Ceritanya berawal ketika saya menjadi orang pertama di Bank TLM yang terkena penyakit Covid-19 pada tahun 2020, ketika pandemic covid-19 merebak pertama di wilayah Provinsi NTT,” kata Edward Mandala mengawali ceritanya.
        </p>
        <a class="nav-link btn btn-primary" href="components.html" style="width: 20%; text-align: center;">SELENGKAPNYA</a></div>
    </div>
    <div class="blog-wrapper">
   
</div>
<div class="blog-wrapper">
    <div class="img-wrapper">
        <img src="assets/imgs/kisah inspiratif 3.png" alt="kisah inspiratif 2">
    </div>
    <div class="txt-wrapper">
    <h4 class="blog-title">Seorang gadis berusia 11 tahun yang selamat berkat transfusi darah sedang memulihkan diri di rumah setelah transplantasi sel punca.</h4>
    <p class="description-blog">
    Chloe Gray dari Sunderland menerima transfusi darah pertamanya saat masih dalam kandungan dan telah keluar masuk rumah sakit untuk perawatan sejak saat itu. Dia menderita penyakit darah langka yang disebut Anemia Blackfan Diamond (DBA) yang berarti tubuhnya tidak dapat memproduksi cukup sel darah merah.Orangtua Chloe, Francesca dan Craig, memulai kampanye untuk menemukan kecocokan dan lebih dari 6.000 orang mendaftar untuk menjadi donor sel punca.
    </p>
        <a class="nav-link btn btn-primary" href="components.html" style="width: 20%; text-align: center;">SELENGKAPNYA</a></div>
    </div>
    <div class="blog-wrapper">
   
</div>

    </section>
    <div class="main-container">
        <div class="content-wrapper">
            <div class="heading">SYARAT MELAKUKAN DONOR DARAH</div>
            <div class="requirements-container">
    <!-- Left Column (Text, Icon) -->
    <div class="requirements-column left">
        <div class="requirement-item">
            <span>Sehat Jasmani dan Rohani</span>
            <img class="requirement-icon" src="assets/imgs/icon 1.png" alt="Sehat Jasmani dan Rohani">
        </div>
        <div class="requirement-item">
            <span>Berat badan minimal 45kg</span>
            <img class="requirement-icon" src="assets/imgs/icon 4.png" alt="Berat Badan">
        </div>
        <div class="requirement-item">
            <span>Tidak minum obat yang mengandung antibiotik dalam seminggu terakhir</span>
            <img class="requirement-icon" src="assets/imgs/icon 8.png" alt="Tidak minum obat">
        </div>
        <div class="requirement-item">
            <span>Usia minimal 17 tahun</span>
            <img class="requirement-icon" src="assets/imgs/icon 5.png" alt="Usia minimal">
        </div>
    </div>

    <!-- Right Column (Icon, Text) -->
    <div class="requirements-column right">
        <div class="requirement-item">
            <img class="requirement-icon" src="assets/imgs/icon 2.png" alt="Tekanan Darah">
            <span>Tekanan darah: Sistol 90-160, Diastol 60-100</span>
        </div>
        <div class="requirement-item">
            <img class="requirement-icon" src="assets/imgs/icon 6.png" alt="Jarak Donor Darah">
            <span>Jarak donor darah sebelumnya 12 minggu atau 3 bulan</span>
        </div>
        <div class="requirement-item">
            <img class="requirement-icon" src="assets/imgs/icon 3.png" alt="Tidak hamil">
            <span>Perempuan tidak sedang hamil dan menyusui</span>
        </div>
        <div class="requirement-item">
            <img class="requirement-icon" src="assets/imgs/icon 7.png" alt="Kadar Hemoglobin">
            <span>Kadar hemoglobin 12.5 - 17 g/dl</span>
        </div>
    </div>
</div>


        </div>
    </div>
    <div class="custom-body">
        <div class="container-card">
            <div class="text-section">
                <h1>Ayo Donor Darah<br>Sekarang!</h1>
                <div class="button-container">
                <button class="btn btn-primary">DONOR SEKARANG!</button>
                </div>
              
            </div>
            <div class="image-section">
                <img src="assets/imgs/ayo donor darah.png" alt="Ayo Donor Darah">
            </div>
        </div>
    </div>

            <footer class="footer">
        <!-- Logo Row -->
         
        <div class="footer-container">
        <div class="footer-logo-row">
            <div class="footer-logo"></div>
            <img src="assets/imgs/logopmi 2.png" alt="Ayo Donor Darah">
            <img src="assets/imgs/line 1.png" alt="Ayo Donor Darah">
            <div class="footer-logo">KABUPATEN <br>NGANJUK </div>
            <div class="footer-logo"></div>
            <img src="assets/imgs/logo nganjuk.png" alt="Ayo Donor Darah">
        </div>
        </div>
        
        
        <div class="footer-container">
            <!-- Address Column -->
            <div class="footer-column">
                <h3>Markas PMI Kabupaten Nganjuk</h3>
                <p>Jl. Mayjen Sungkono No.10, Kauman,
                Kec. Nganjuk, Kabupaten Nganjuk,
                <br>Jawa Timur 64415</p>
                <p class="footer-quote">"Tidak ada sukacita yang lebih besar daripada menyelamatkan satu jiwa."</p>
            </div>

            <!-- Services Column -->
            <div class="footer-column">
                <h3>Layanan</h3>
                <p>Unit Donor Darah</p>
                <p>Markas</p>
            </div>

            <!-- Help Column -->
            <div class="footer-column">
                <h3>Beri Bantuan</h3>
                <p>Donor Darah</p>
                <p>Donasi</p>
            </div>

            <!-- Social Media Column -->
            <div class="footer-column">
                <h3>Sosial Media Kami</h3>
                <div class="social-media-item">
                    <img src="assets/imgs/icons8-instagram-48 1.png" alt="">
                    <a href="#">@udd.pminganjuk</a>
                </div>
                <div class="social-media-item">
                <img src="assets/imgs/icons8-whatsapp-100 1.png" alt="">
                    <a href="tel:+6285142279393">+62 851 4227 9393</a>
                </div>
                <div class="social-media-item">
                <img src="assets/imgs/icons8-facebook-circled-50 2.png" alt="">
                    <p>udd pmi nganjuk</p>
                </div>
                <div class="social-media-item">
                <img src="assets/imgs/icons8-gmail-99 1.png" alt="">
                    <a href="mailto:udd.pmi.nganjuk@gmail.com">udd.pmi.nganjuk@gmail.com</a>
                </div>
            </div>
        </div>
    </footer>
        </div>
	
	<!-- core  -->
    <script src="assets/vendors/jquery/jquery-3.4.1.js"></script>
    <script src="assets/vendors/bootstrap/bootstrap.bundle.js"></script>

    <!-- bootstrap 3 affix -->
	<script src="assets/vendors/bootstrap/bootstrap.affix.js"></script>
    
    <!-- Owl carousel  -->
    <script src="assets/vendors/owl-carousel/js/owl.carousel.js"></script>


    <!-- Ollie js -->
    <script src="assets/js/Ollie.js"></script>

</body>

<style>

     .horizontal-list {
            display: flex;
            flex-wrap: wrap; /* Allows wrapping to next row on smaller screens */
            padding: 20px;
            gap: 10px; /* Spacing between items */
            justify-content: space-between; /* Spacing adjustments between items */
        }

        /* List Item Style */
        .list-item {
            flex: 1 1 calc(25% - 10px); /* Set each item to take 25% width minus gap */
            background-color: #DF3232;
            border-radius: 10px;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Image Style */
        .list-item img {
            width: 100px; /* Bigger image size */
            height: 100px;
            margin-bottom: 15px;
            border-radius: 50%;
            background-color: #ffffff;
            padding: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Text Style */
        .list-item h6 {
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            margin: 10px 0 0;
        }

        /* Responsive Adjustment */
        @media (max-width: 768px) {
            .list-item {
                flex: 1 1 calc(50% - 10px); /* On smaller screens, each item takes 50% width */
            }
        }

        @media (max-width: 480px) {
            .list-item {
                flex: 1 1 calc(100% - 10px); /* On very small screens, each item takes full width */
            }
        }
/* Custom body class to avoid global body styles */
.custom-body {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f0f0f0;
    font-family: Arial, sans-serif;
}


/* Full-width container card styling with fixed height */
.container-card {
    display: flex;
    align-items: center;
    justify-content: space-between; /* space between text and image */
    padding: 20px;
    
    margin-bottom: 40px;
    margin-top: 40px;
    margin-right: 40px;
    margin-left: 40px;
    background-color: #ffffff;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%; /* Full width */
    height: 500px; /* Fixed height */
    max-width: 114%; /* Ensure it covers full screen width */
    box-sizing: border-box; /* Includes padding in width calculation */
    transition: width 0.3s ease;
}
/* Text section styling */
.text-section {
    flex: 1;
    font-size: 4rem; /* Further increased font size */
    font-weight: bold;
    color: #000;
}

/* Image section styling */
.image-section {
    flex: 0 0 auto;
    margin-left: 20px;
}

.image-section img {
    width: 300px; /* Further increased image size */
    height: auto;
    border-radius: 50%;
}

/* Media query for smaller screens */
@media (max-width: 600px) {
    .container-card {
        flex-direction: column;
        text-align: center;
        width: 100%;
    }

    .text-section {
        margin-bottom: 10px;
        font-size: 2.5rem; /* Increased font size for smaller screens */
    }

    .image-section {
        margin-left: 0;
    }

    .image-section img {
        width: 120px; /* Larger image size on smaller screens */
    }
}
</style>
<style>
    .description-blog{
        padding-right: 20%;
    }
        .footer {
            background-color: #4b4b4b;
            color: white;
            padding: 40px 20px;
        }
        .footer-logo-row {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 40px;
        }
        .footer-logo {
            font-size: 18px;
            font-weight: bold;
        }
        .footer-divider {
            border-left: 1px solid #ffffff;
            height: 30px;
        }
        .footer-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .footer-column {
            flex: 1;
            min-width: 200px;
            text-align-last: center;
        }
        .footer-column h3 {
            color: #ff5151;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .footer-column p, .footer-column a {
            font-size: 14px;
            color: white;
            margin: 5px 0;
            text-decoration: none;
        }
        .footer-column a:hover {
            text-decoration: underline;
        }
        .social-media-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }
        .social-icon {
            width: 30px;
            height: 30px;
            background-color: #cccccc;
            border-radius: 5px;
        }
        .footer-quote {
            font-style: italic;
            font-size: 14px;
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            .footer-container {
                flex-direction: column;
                text-align: center;
            }
            .footer-logo-row {
                justify-content: center;
            }
            .footer-divider {
                display: none;
            }
            .footer-quote {
                margin-top: 10px;
            }
        }
    </style>
</html>
