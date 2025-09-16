<?php
// index.php - Modern Home Page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLUSH-D | Elevate Your Beauty</title>
    <link rel="stylesheet" href="./assets/global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./components/navigation/navigation.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./components/footer/footer.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="./assets/global.js" defer></script>
</head>
<body>
    <header>
        <?php include './components/navigation/navigation.php'; ?>
    </header>

    <!-- BLUSH-D Hero Section -->
    <section class="landing-hero">
        <div class="hero-caption">
            <h1><span class="highlight">BLUSH-D</span>Elevate Your Beauty</h1>
            <p>Discover premium cosmetics designed exclusively for you. BLUSH-D brings quality, innovation, and confidence to your beauty routine with our carefully crafted collection.</p>
        </div>
    </section>

    <!-- BLUSH-D Brand Introduction -->
    <section class="brand-story">
        <div class="brand-story-container">
            <div class="brand-story-header">
                <h2>The BLUSH-D Story</h2>
                <p>Where beauty meets confidence</p>
            </div>
            <div class="brand-story-content">
                <div class="story-text">
                    <p>At BLUSH-D, we believe beauty is about feeling confident and authentically yourself. Our premium products are crafted with the finest ingredients to enhance your natural radiance.</p>
                </div>
                <div class="brand-values">
                    <div class="value-card">
                        <h4>Premium Quality</h4>
                        <p>Finest ingredients sourced globally</p>
                    </div>
                    <div class="value-card">
                        <h4>Natural Beauty</h4>
                        <p>Enhance your authentic self</p>
                    </div>
                    <div class="value-card">
                        <h4>Trusted Formula</h4>
                        <p>Dermatologically tested products</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose BLUSH-D -->
    <section class="why-choose-us" style="background: white; padding: 4rem 2rem;">
        <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 2.8rem; color: var(--text-dark); margin-bottom: 3rem;">
                Why Choose BLUSH-D
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                <div style="text-align: left; padding: 2rem; border-radius: 15px; background: var(--background-soft); border-left: 4px solid var(--brand-primary);">
                    <h4 style="color: var(--brand-primary); font-weight: 600; margin-bottom: 1rem; font-size: 1.1rem;">Premium Quality</h4>
                    <p style="color: var(--text-light); line-height: 1.6;">Every BLUSH-D product is crafted with the finest ingredients and tested for safety and effectiveness.</p>
                </div>
                <div style="text-align: left; padding: 2rem; border-radius: 15px; background: var(--background-soft); border-left: 4px solid var(--brand-primary);">
                    <h4 style="color: var(--brand-primary); font-weight: 600; margin-bottom: 1rem; font-size: 1.1rem;">Skin-Friendly Formula</h4>
                    <p style="color: var(--text-light); line-height: 1.6;">Our products are dermatologically tested and suitable for all skin types, including sensitive skin.</p>
                </div>
                <div style="text-align: left; padding: 2rem; border-radius: 15px; background: var(--background-soft); border-left: 4px solid var(--brand-primary);">
                    <h4 style="color: var(--brand-primary); font-weight: 600; margin-bottom: 1rem; font-size: 1.1rem;">Sustainable Beauty</h4>
                    <p style="color: var(--text-light); line-height: 1.6;">We're committed to eco-friendly packaging and cruelty-free beauty practices.</p>
                </div>
                <div style="text-align: left; padding: 2rem; border-radius: 15px; background: var(--background-soft); border-left: 4px solid var(--brand-primary);">
                    <h4 style="color: var(--brand-primary); font-weight: 600; margin-bottom: 1rem; font-size: 1.1rem;">Expert Support</h4>
                    <p style="color: var(--text-light); line-height: 1.6;">Get personalized beauty advice from our certified makeup artists and skincare specialists.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- BLUSH-D Product Categories -->
    <section class="collection-section">
        <div class="collection-container">
            <div class="collection-header">
                <h2>Our Collection</h2>
                <p>Discover what makes BLUSH-D special - our commitment to quality and beauty innovation</p>
            </div>
            <div class="collection-grid">
                <div class="collection-card">
                    <div class="card-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3>Makeup</h3>
                    <p>Professional-grade foundations, lipsticks, eyeshadows and more. Each product is formulated with premium ingredients for flawless application.</p>
                </div>
                
                <div class="collection-card">
                    <div class="card-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Skincare</h3>
                    <p>Advanced skincare solutions with scientifically-proven ingredients. Our cleansers, serums, and moisturizers work together for radiant skin.</p>
                </div>
                
                <div class="collection-card">
                    <div class="card-icon">
                        <i class="fas fa-cut"></i>
                    </div>
                    <h3>Hair Care</h3>
                    <p>Luxurious hair care formulations that nourish from root to tip. Our treatments restore hair's natural strength and shine.</p>
                </div>
                
                <div class="collection-card">
                    <div class="card-icon">
                        <i class="fas fa-spa"></i>
                    </div>
                    <h3>Body Care</h3>
                    <p>Indulgent body care essentials that pamper and protect. Our lotions and scrubs leave skin feeling silky smooth.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- BLUSH-D Customer Testimonials -->
    <section class="testimonials" style="background: var(--background-soft); padding: 4rem 2rem;">
        
        <h2>What Our Customers Say</h2>
        <div id="review-list"></div>

    </section>

    <!-- BLUSH-D Brand Philosophy -->
    <section style="background: white; padding: 4rem 2rem;">
        <div style="max-width: 1000px; margin: 0 auto; text-align: center;">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 2.8rem; color: var(--text-dark); margin-bottom: 3rem;">
                Our Philosophy
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem; margin-top: 3rem;">
                <div style="text-align: center;">
                    <div style="width: 80px; height: 80px; background: var(--brand-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2rem; color: white;">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4 style="color: var(--brand-primary); font-weight: 600; margin-bottom: 1rem; font-size: 1.3rem;">Beauty is Personal</h4>
                    <p style="color: var(--text-light); line-height: 1.6;">We believe every person deserves products that celebrate their unique beauty and enhance their natural confidence.</p>
                </div>
                
                <div style="text-align: center;">
                    <div style="width: 80px; height: 80px; background: var(--brand-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2rem; color: white;">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h4 style="color: var(--brand-primary); font-weight: 600; margin-bottom: 1rem; font-size: 1.3rem;">Conscious Creation</h4>
                    <p style="color: var(--text-light); line-height: 1.6;">Our commitment to sustainable practices and ethical sourcing ensures beauty that's good for you and the planet.</p>
                </div>
                
                <div style="text-align: center;">
                    <div style="width: 80px; height: 80px; background: var(--brand-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2rem; color: white;">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h4 style="color: var(--brand-primary); font-weight: 600; margin-bottom: 1rem; font-size: 1.3rem;">Uncompromising Quality</h4>
                    <p style="color: var(--text-light); line-height: 1.6;">From formulation to packaging, every detail is carefully considered to deliver products that exceed expectations.</p>
                </div>
            </div>
        </div>
    </section>

    

    <!-- Hidden Main Content for SEO -->
    <main style="display:none;">
        <h1>BLUSH-D - Premium Beauty Brand</h1>
        <p>Discover BLUSH-D's exclusive collection of makeup, skincare, hair care, and body care products designed to elevate your beauty.</p>
    </main>

    <script>
    // Simple smooth scroll for anchor links
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
