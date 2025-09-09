<?php
// footer.php - reusable footer
?>
<footer>
    <div class="footer-content">
        <div class="footer-section company-info">
            <h3>Beauty Store</h3>
            <div class="contact-info">
                <div class="contact-item">
                    <img class="contact-icon" src="https://img.icons8.com/ios-filled/50/marker.png" alt="Location" />
                    <span>123 Beauty Street, Fashion District<br>New York, NY 10001</span>
                </div>
                <div class="contact-item">
                    <img class="contact-icon" src="https://img.icons8.com/ios-filled/50/phone.png" alt="Phone" />
                    <span>+1 (555) 123-4567</span>
                </div>
                <div class="contact-item">
                    <img class="contact-icon" src="https://img.icons8.com/ios-filled/50/new-post.png" alt="Email" />
                    <span>info@beautystore.com</span>
                </div>
            </div>
        </div>

        <div class="footer-section read-only-info">
            <h3>Information</h3>
            <p>Welcome to Blush-D! For general information, please contact your manager or support. This section is for reading only.</p>
        </div>

        <div class="footer-section customer-service">
            <h3>Customer Service</h3>
            <ul>
                <li><a href="shipping.php">Shipping Info</a></li>
                <li><a href="returns.php">Returns & Exchanges</a></li>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="privacy.php">Privacy Policy</a></li>
                <li><a href="terms.php">Terms of Service</a></li>
                <li><a href="support.php">Support</a></li>
            </ul>
        </div>

        <div class="footer-section social-newsletter">
            <h3>Stay Connected</h3>
            <div class="social-links">
                <a href="https://facebook.com" target="_blank" class="social-link facebook">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                
                <a href="https://instagram.com" target="_blank" class="social-link instagram">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.621 5.367 11.988 11.988 11.988c6.62 0 11.988-5.367 11.988-11.988C24.005 5.367 18.637.001 12.017.001z"/>
                        <circle cx="12" cy="12" r="3.5"/>
                        <circle cx="17.5" cy="6.5" r="1.5"/>
                    </svg>
                </a>
                
                <a href="https://youtube.com" target="_blank" class="social-link youtube">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                </a>
                
                <a href="https://twitter.com" target="_blank" class="social-link twitter">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                </a>
            </div>

            <div class="newsletter">
                <h4>Newsletter</h4>
                <form class="newsletter-form">
                    <input type="email" placeholder="Enter your email" required>
                    <button type="submit">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="footer-bottom-content">
            <p>&copy; 2025 Beauty Store. All rights reserved.</p>
            <div class="payment-methods">
                <span>We Accept:</span>
                <div class="payment-icons">
                    <span class="payment-icon">💳</span>
                    <span class="payment-icon">💰</span>
                    <span class="payment-icon">🏧</span>
                </div>
            </div>
        </div>
    </div>
</footer>