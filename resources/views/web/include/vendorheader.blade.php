<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwiklly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{ asset('public/assets/website/CSS/style.css')}}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<style>
    .desktop-menu a {
    font-size: 16px;
    font-weight: 400;
    margin: 0px 20px 0px 20px;
    text-decoration: none;
    color: #ffffff;
    transition: color 0.3s;
}
@media (max-width: 768px) {
    .cart-btn a {
    font-size: 16px;
    font-weight: 400;
    margin: 0px 0px 0px a0px;
    text-decoration: none;
    color: #ffffff;
    transition: color 0.3s;
}
 }
</style>


<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">        
        <!-- Desktop: Logo + Location & Search -->
        <div class="d-flex align-items-center w-100 d-none d-md-flex">
            <a class="navbar-brand" href="index.php">
                <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="Logo">
            </a>           
        </div>        
        <!-- Desktop Menu (Hidden in Mobile) -->
        <div class="d-flex align-items-center desktop-menu">           
            <div class="cart-btn">
              <a href="login.php">Join&nbsp;Us</a> 
            </div>    
        </div>        
    </div>
    <div class="mobile-top d-md-none">
            <a class="navbar-brand" href="index.php">
                <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="Logo">
            </a> 
            <div class="cart-btn">
              <a href="login.php">Join&nbsp;Us</a> 
            </div>  
        </div>
</nav>