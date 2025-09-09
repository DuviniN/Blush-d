<?php
// index.php - Modern Home Page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLUSH-D | Premium Beauty & Cosmetics</title>
    <link rel="stylesheet" href="./assets/global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./components/navigation/navigation.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./components/footer/footer.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="./assets/global.js" defer></script>
    <style>
        .animated-fadein {
            opacity: 0;
            animation: fadeInUp 1s forwards 4s;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .pro-features {
            background: var(--soft-white);
        }
    </style>
</head>
<body>
    <header>
        <?php include './components/navigation/navigation.php'; ?>
    </header>

    <!-- Modern Hero Section with Premium Slider -->
    <section class="landing-hero">
        <div class="hero-slider">
            <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=1920&q=80" class="hero-slide active" alt="Premium Cosmetics Collection">
            <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1920&q=80" class="hero-slide" alt="Luxury Beauty Products">
            <img src="https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?auto=format&fit=crop&w=1920&q=80" class="hero-slide" alt="Modern Beauty Studio">
            <img src="https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=1920&q=80" class="hero-slide" alt="Professional Makeup">
            <div class="hero-overlay"></div>
            <div class="slider-controls">
                <span class="slider-dot active"></span>
                <span class="slider-dot"></span>
                <span class="slider-dot"></span>
                <span class="slider-dot"></span>
            </div>
        </div>
        <div class="hero-caption animated-fadein">
            <h1><span class="highlight">Discover</span> Your Perfect Look</h1>
            <p>Elevate your beauty routine with our curated collection of premium cosmetics, skincare essentials, and professional beauty tools. Where luxury meets everyday elegance.</p>
            <a href="../Pages/register/register.php" class="cta-btn">Start Your Journey</a>
            <a href="#brands" class="cta-btn cta-secondary">Explore Brands</a>
        </div>
    </section>

    <!-- Modern Trusted By Section -->
    <section class="trusted-by">
        <h2 class="trusted-title">Partnered with Industry Leaders</h2>
        <div class="brand-logos-modern">
            <div class="brand-logo-card">
                <img src="./assets/pictures/L'Oreal/images (1).jpeg" alt="L'Oreal Paris">
                <span class="brand-name">L'Oreal</span>
            </div>
            <div class="brand-logo-card">
                <img src="./assets/pictures/Maybelline/images.jpeg" alt="Maybelline New York">
                <span class="brand-name">Maybelline</span>
            </div>
            <div class="brand-logo-card">
                <img src="./assets/pictures/Sephora/download.jpeg" alt="Sephora Beauty">
                <span class="brand-name">Sephora</span>
            </div>
            <div class="brand-logo-card">
                <img src="./assets/pictures/Estee_Lauder/images.jpeg" alt="Estée Lauder">
                <span class="brand-name">Estée Lauder</span>
            </div>
            <div class="brand-logo-card">
                <img src="./assets/pictures/Revlon/images.jpeg" alt="Revlon Professional">
                <span class="brand-name">Revlon</span>
            </div>
        </div>
    </section>

    <!-- Why Choose Us - Modern Approach -->
    <section class="why-choose-us">
        <h2>Why Beauty Enthusiasts Choose Us</h2>
        <ul>
            <li><strong>Authenticity Guaranteed:</strong> Every product is sourced directly from authorized distributors, ensuring 100% genuine cosmetics and skincare.</li>
            <li><strong>Expert Beauty Consultations:</strong> Our certified beauty advisors provide personalized recommendations based on your skin type, tone, and style preferences.</li>
            <li><strong>Exclusive Member Benefits:</strong> Access to limited-edition collections, early product launches, and member-only discounts up to 30% off.</li>
            <li><strong>Sustainable Beauty:</strong> We prioritize eco-conscious brands and sustainable packaging to minimize environmental impact.</li>
            <li><strong>Premium Service:</strong> Same-day shipping in metro areas, complimentary gift wrapping, and hassle-free 30-day returns.</li>
        </ul>
    </section>

    <!-- Modern Features Section -->
    <section class="pro-features">
        <div class="features-section">
            <div class="feature-card">
                <div class="feature-icon">💄</div>
                <h3>Makeup Mastery</h3>
                <p>Professional-grade cosmetics from cult favorites to luxury brands. Create stunning looks with our expertly curated makeup collection.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌟</div>
                <h3>Skincare Science</h3>
                <p>Advanced skincare solutions backed by dermatological research. Transform your skin with clinically proven ingredients and formulations.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">✨</div>
                <h3>Beauty Tools</h3>
                <p>Professional brushes, applicators, and beauty devices. Achieve flawless application with tools used by makeup artists worldwide.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🚀</div>
                <h3>Express Delivery</h3>
                <p>Lightning-fast shipping with real-time tracking. Premium packaging ensures your beauty products arrive in perfect condition.</p>
            </div>
        </div>
    </section>

    <!-- Modern Popular Brands Carousel -->
    <section class="popular-brands" id="brands">
        <h2>Featured Beauty Brands</h2>
        <div class="brands-carousel">
            <div class="brand-item"><img src="./assets/pictures/L'Oreal/images (1).jpeg" alt="L'Oreal Paris"></div>
            <div class="brand-item"><img src="./assets/pictures/Maybelline/images.jpeg" alt="Maybelline New York"></div>
            <div class="brand-item"><img src="./assets/pictures/Sephora/download.jpeg" alt="Sephora Collection"></div>
            <div class="brand-item"><img src="./assets/pictures/Estee_Lauder/images.jpeg" alt="Estée Lauder"></div>
            <div class="brand-item"><img src="./assets/pictures/Revlon/images.jpeg" alt="Revlon Professional"></div>
            <div class="brand-item"><img src="./assets/pictures/Clinique/download.jpeg" alt="Clinique"></div>
            <div class="brand-item"><img src="./assets/pictures/L'Oreal/images (1).jpeg" alt="L'Oreal Paris"></div>
            <div class="brand-item"><img src="./assets/pictures/Maybelline/images.jpeg" alt="Maybelline New York"></div>
        </div>
    </section>

    <!-- Modern Testimonials -->
    <section class="testimonials">
        <h2>What Beauty Lovers Are Saying</h2>
        <div class="testimonial-list">
            <div class="testimonial">
                <p>"The quality and authenticity of products here is unmatched. My skincare routine has completely transformed, and I've never felt more confident in my skin."</p>
                <span>— Ayesha K., Lahore</span>
            </div>
            <div class="testimonial">
                <p>"From ordering to delivery, everything was seamless. The expert recommendations helped me find my perfect foundation shade on the first try!"</p>
                <span>— Sara M., Karachi</span>
            </div>
            <div class="testimonial">
                <p>"Finally, a beauty store that understands what modern women need. Premium products, sustainable packaging, and incredible customer service."</p>
                <span>— Fatima R., Islamabad</span>
            </div>
        </div>
    </section>

    <!-- Modern Newsletter Signup -->
    <section class="newsletter-signup">
        <h2>Stay In The Beauty Loop</h2>
        <p>Get exclusive access to new launches, beauty tips, and special offers delivered to your inbox.</p>
        <form class="newsletter-form">
            <input type="email" placeholder="Enter your email for beauty updates" required>
            <button type="submit">Subscribe Now</button>
        </form>
    </section>

    <!-- Hidden Main Content for SEO -->
    <main style="display:none;">
        <h1>BLUSH-D - Premium Beauty & Cosmetics Store</h1>
        <p>Discover the finest selection of cosmetics, skincare, and beauty tools from world-renowned brands.</p>
    </main>

    <script>
    // Enhanced Professional Slider with Smooth Transitions
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');
    const caption = document.querySelector('.hero-caption');
    
    function showSlide(idx) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === idx);
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === idx);
        });
        
        // Animate caption
        caption.style.transform = 'translateY(-50%) scale(0.95)';
        caption.style.opacity = '0.8';
        
        setTimeout(() => {
            caption.style.transform = 'translateY(-50%) scale(1)';
            caption.style.opacity = '1';
        }, 300);
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }
    
    // Dot navigation
    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            currentSlide = i;
            showSlide(currentSlide);
        });
    });
    
    // Auto-advance slider
    setInterval(nextSlide, 5000);
    
    // Newsletter form submission
    document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        const button = this.querySelector('button');
        const originalText = button.textContent;
        
        button.textContent = 'Subscribing...';
        button.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            button.textContent = 'Subscribed!';
            button.style.background = 'linear-gradient(135deg, #00c851, #00a84c)';
            
            setTimeout(() => {
                button.textContent = originalText;
                button.disabled = false;
                button.style.background = '';
                this.reset();
            }, 2000);
        }, 1000);
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    </script>

    <?php include './components/footer/footer.php'; ?>
</body>
</html>
