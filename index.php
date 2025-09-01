<?php
// index.php - Home Page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home | My Website</title>
    <link rel="stylesheet" href="./assets/global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./components/navigation/navigation.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./components/footer/footer.css?v=<?php echo time(); ?>">
    <script src="./assets/global.js" defer></script>
</head>
<body>
    <header>
        <?php include './components/navigation/navigation.php'; ?>   <!-- Loads the nav bar -->
    </header>
    <!-- Professional Landing Hero Section with Animated Slider -->
    <section class="landing-hero">
        <div class="hero-slider">
            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=1200&q=80" class="hero-slide active" alt="Cosmetic Beauty 1">
            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80" class="hero-slide" alt="Cosmetic Beauty 2">
            <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=1200&q=80" class="hero-slide" alt="Cosmetic Beauty 3">
            <img src="https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=1200&q=80" class="hero-slide" alt="Cosmetic Beauty 4">
            <div class="hero-overlay"></div>
            <div class="slider-controls">
                <span class="slider-dot active"></span>
                <span class="slider-dot"></span>
                <span class="slider-dot"></span>
                <span class="slider-dot"></span>
            </div>
        </div>
        <div class="hero-caption animated-fadein">
            <h1><span class="highlight">Unleash</span> Your True Beauty</h1>
            <p>Premium cosmetics, skincare, and beauty tools for every style.<br>Shop the latest trends and glow with confidence!</p>
            <a href="../Pages/register/register.php" class="cta-btn">Get Started</a>
            <a href="#brands" class="cta-btn cta-secondary">See Brands</a>
        </div>
    </section>
    <!-- Trusted By Section -->
    <section class="trusted-by">
        <h2>Trusted By Leading Cosmetic Brands</h2>
        <div class="brand-logos">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6e/L%27Or%C3%A9al_logo.svg" alt="L'Oreal">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2e/Maybelline_logo.svg" alt="Maybelline">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2d/Sephora_logo.svg" alt="Sephora">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2c/Estee_Lauder_Companies_logo.svg" alt="Estee Lauder">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Revlon_logo.svg" alt="Revlon">
        </div>
    </section>
    <!-- Why Choose Us Section -->
    <section class="why-choose-us">
        <h2>Why Choose Our Cosmetic Store?</h2>
        <ul>
            <li><strong>100% Authentic Products:</strong> We guarantee genuine, high-quality cosmetics from top brands worldwide.</li>
            <li><strong>Expert Advice:</strong> Our beauty consultants help you find the perfect products for your skin, hair, and style.</li>
            <li><strong>Exclusive Offers:</strong> Enjoy members-only discounts, gifts, and early access to new launches.</li>
            <li><strong>Eco-Friendly Packaging:</strong> We care for the planet with sustainable, recyclable packaging.</li>
            <li><strong>Fast, Secure Delivery:</strong> Get your beauty essentials delivered quickly and safely to your door.</li>
        </ul>
    </section>
    <!-- Features Section -->
    <section class="features-section pro-features">
        <div class="feature-card">
            <div class="feature-icon" style="background:linear-gradient(135deg,#ff7eb3,#ff758c);color:#fff;">💄</div>
            <h3>Top Cosmetics</h3>
            <p>Shop the best makeup brands and trending products for every look. Curated by beauty experts.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:linear-gradient(135deg,#a8edea,#fed6e3);color:#ff7eb3;">🧴</div>
            <h3>Skincare Essentials</h3>
            <p>Glow with dermatologist-recommended skincare for all skin types. Clean, safe, and effective.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:linear-gradient(135deg,#fcb69f,#ffecd2);color:#ff758c;">💇‍♀️</div>
            <h3>Hair Perfection</h3>
            <p>Find nourishing haircare and styling tools for salon results at home. Shine every day.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:linear-gradient(135deg,#43e97b,#38f9d7);color:#fff;">🚚</div>
            <h3>Fast Delivery</h3>
            <p>Enjoy quick, reliable shipping and easy returns on every order. 24/7 support.</p>
        </div>
    </section>
    <!-- Popular Brands Carousel -->
    <section class="popular-brands" id="brands">
        <h2>Popular Brands</h2>
        <div class="brands-carousel">
            <div class="brand-item"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6e/L%27Or%C3%A9al_logo.svg/320px-L%27Or%C3%A9al_logo.svg.png" alt="L'Oreal"></div>
            <div class="brand-item"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/Maybelline_logo.svg/320px-Maybelline_logo.svg.png" alt="Maybelline"></div>
            <div class="brand-item"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2d/Sephora_logo.svg/320px-Sephora_logo.svg.png" alt="Sephora"></div>
            <div class="brand-item"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Estee_Lauder_Companies_logo.svg/320px-Estee_Lauder_Companies_logo.svg.png" alt="Estee Lauder"></div>
            <div class="brand-item"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Revlon_logo.svg/320px-Revlon_logo.svg.png" alt="Revlon"></div>
            <div class="brand-item"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Clinique_logo.svg/320px-Clinique_logo.svg.png" alt="Clinique"></div>
            <div class="brand-item"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/Maybelline_logo.svg/320px-Maybelline_logo.svg.png" alt="Maybelline"></div>
        </div>
    </section>
    <!-- Testimonials Section -->
    <section class="testimonials">
        <h2>What Our Customers Say</h2>
        <div class="testimonial-list">
            <div class="testimonial">
                <p>“Absolutely love the variety and quality! My skin has never looked better.”</p>
                <span>- Ayesha, Lahore</span>
            </div>
            <div class="testimonial">
                <p>“Fast delivery and genuine products. Highly recommended for beauty lovers!”</p>
                <span>- Sara, Karachi</span>
            </div>
            <div class="testimonial">
                <p>“The expert advice helped me pick the perfect foundation. Thank you!”</p>
                <span>- Fatima, Islamabad</span>
            </div>
        </div>
    </section>
    <!-- Newsletter Signup -->
    <section class="newsletter-signup">
        <h2>Stay Updated!</h2>
        <form class="newsletter-form">
            <input type="email" placeholder="Enter your email for beauty tips & offers" required>
            <button type="submit" class="cta-btn">Subscribe</button>
        </form>
    </section>
    <main>
        <h1 style="display:none">Welcome to My Website</h1>
        <p style="display:none">This is the homepage of your project.</p>
    </main>
    <script>
    // Professional JS slider for hero images with controls and animated caption
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');
    const caption = document.querySelector('.hero-caption');
    function showSlide(idx) {
      slides.forEach((el, i) => {
        el.classList.toggle('active', i === idx);
      });
      dots.forEach((el, i) => {
        el.classList.toggle('active', i === idx);
      });
      caption.classList.remove('animated-fadein');
      void caption.offsetWidth;
      caption.classList.add('animated-fadein');
    }
    function nextSlide() {
      currentSlide = (currentSlide + 1) % slides.length;
      showSlide(currentSlide);
    }
    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => {
        currentSlide = i;
        showSlide(currentSlide);
      });
    });
    setInterval(nextSlide, 4000);
    </script>
   
   <?php include './components/footer/footer.php'; ?>
  

    
</body>
</html>
