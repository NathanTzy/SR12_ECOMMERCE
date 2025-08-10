<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SR12 Official Shop Palembang</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/images/sr12.png') }}">

    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/templatemo-hexashop.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/owl-carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/lightbox.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


    <style>
        .custom-carousel-wrapper {
            position: relative;
        }

        .custom-carousel {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 10px 0;
        }

        .custom-carousel::-webkit-scrollbar {
            display: none;
        }

        .custom-carousel-item {
            flex: 0 0 auto;
            background: #fff;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .custom-carousel-item:hover {
            transform: translateY(-5px);
        }

        .custom-carousel-item .thumb {
            width: 100%;
            height: 250px;
            overflow: hidden;
            position: relative;
        }

        .custom-carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .custom-carousel-item .down-content {
            padding: 15px;
            text-align: center;
        }

        .custom-carousel-item h4 {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 5px;
        }

        .custom-carousel-item span {
            display: block;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .custom-carousel-item .stars {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            gap: 4px;
        }

        /* Responsive Item Width */
        @media (min-width: 992px) {
            .custom-carousel-item {
                width: calc((100% - 60px) / 4);
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {
            .custom-carousel-item {
                width: calc((100% - 40px) / 3);
            }
        }

        @media (max-width: 767px) {
            .custom-carousel-item {
                width: calc((100% - 10px) / 2);
            }
        }


        .img-container {
            width: 100%;
            height: 277px;
            overflow: hidden;
        }

        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-card {
            transition: all 0.3s ease;
            border: none;

        }

        .product-card:hover {

            transform: translateY(-3px);
        }


        .img-thumb-box {
            width: 100%;
            height: 250px;
            overflow: hidden;
        }

        .img-thumb-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .content-offset {
            margin-top: 100px;
        }

        .btn-cart {
            background-color: #000000;
            color: #fff;
            border-radius: 0;
            box-shadow: none;
            font-weight: 500;
            padding: 12px 0;
            font-size: 1rem;
            text-align: center;
            display: inline-block;
            border: none;
        }

        .btn-cart:hover {
            background-color: #2d2d2d;
            color: #fff;
        }

        .modal-header.bg-primary {
            background-color: #292929 !important;
        }

        .btn-close-white {
            filter: brightness(0) invert(1);
        }

        .form-label {
            font-weight: 500;
        }
    </style>
</head>

<body>
    @include('pages.frontend.components.navbar')

    @yield('content')

    @include('pages.frontend.components.footer')

    <!-- Scripts -->
    <script src="{{ asset('frontend/js/jquery-2.1.0.min.js') }}"></script>
    <script src="{{ asset('frontend/js/popper.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('frontend/js/owl-carousel.js') }}"></script>
    <script src="{{ asset('frontend/js/accordions.js') }}"></script>
    <script src="{{ asset('frontend/js/datepicker.js') }}"></script>
    <script src="{{ asset('frontend/js/scrollreveal.min.js') }}"></script>
    <script src="{{ asset('frontend/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('frontend/js/imgfix.min.js') }}"></script>
    <script src="{{ asset('frontend/js/slick.js') }}"></script>
    <script src="{{ asset('frontend/js/lightbox.js') }}"></script>
    <script src="{{ asset('frontend/js/isotope.js') }}"></script>
    <script src="{{ asset('frontend/js/custom.js') }}"></script>

    


    @stack('scripts')

</body>

</html>
