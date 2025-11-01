<?php include 'backend/views/header.php'; ?>

<!-- Contact page content -->
<style>
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}
.contact-hero {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 50%, #c44569 100%);
    color: #fff;
    padding: 80px 0;
    text-align: center;
    margin-bottom: 0;
    position: relative;
    overflow: hidden;
}
.contact-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 30% 50%, rgba(255,255,255,0.1) 0%, transparent 50%);
}
.contact-hero h1 {
    font-size: 3.5rem;
    margin: 0 0 20px 0;
    font-weight: 800;
    position: relative;
    z-index: 1;
    text-shadow: 0 4px 20px rgba(0,0,0,0.2);
}
.contact-hero p {
    font-size: 1.3rem;
    opacity: 0.95;
    max-width: 700px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
    font-weight: 300;
}
.contact-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 60px 20px 80px;
}
.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 30px;
    margin-top: 0;
}
.info-cards {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.info-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
    border-radius: 20px;
    padding: 35px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    transition: transform 0.3s, box-shadow 0.3s;
    border: 2px solid rgba(255,255,255,0.3);
    backdrop-filter: blur(10px);
}
.info-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 50px rgba(0,0,0,0.25);
}
.info-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    font-size: 32px;
    color: #fff;
    box-shadow: 0 10px 30px rgba(245, 87, 108, 0.4);
    transition: transform 0.3s;
}
.info-card:hover .info-icon {
    transform: rotate(360deg) scale(1.1);
}
.info-card h3 {
    text-align: center;
    color: #2c3e50;
    font-size: 1.6rem;
    margin: 0 0 20px 0;
    font-weight: 700;
}
.info-card p {
    text-align: center;
    color: #495057;
    line-height: 1.9;
    margin: 0;
    font-size: 1.05rem;
}
.info-card p strong {
    color: #2c3e50;
    display: block;
    margin-top: 12px;
    font-weight: 600;
}
.contact-form-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
    border-radius: 20px;
    padding: 45px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    border: 2px solid rgba(255,255,255,0.3);
    backdrop-filter: blur(10px);
}
.contact-form-card h3 {
    color: #2c3e50;
    font-size: 2rem;
    margin: 0 0 15px 0;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 15px;
}
.contact-form-card h3 i {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: 1.8rem;
}
.form-subtitle {
    color: #6c757d;
    margin: 0 0 35px 0;
    font-size: 1.05rem;
    font-weight: 400;
}
.form-group {
    margin-bottom: 25px;
}
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid #e0e6ed;
    border-radius: 12px;
    font-size: 1.05rem;
    transition: all 0.3s ease;
    font-family: inherit;
    background: rgba(255,255,255,0.8);
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #f093fb;
    box-shadow: 0 0 0 4px rgba(240, 147, 251, 0.2);
    background: #fff;
    transform: translateY(-2px);
}
.form-group textarea {
    resize: vertical;
    min-height: 150px;
}
.submit-btn {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: #fff;
    border: none;
    padding: 18px 45px;
    font-size: 1.15rem;
    font-weight: 700;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 10px 30px rgba(250, 112, 154, 0.4);
    text-transform: uppercase;
    letter-spacing: 1px;
}
.submit-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(250, 112, 154, 0.5);
}
.submit-btn i {
    font-size: 1.2rem;
}
#contactResult {
    margin-top: 20px;
    padding: 15px;
    border-radius: 8px;
    font-weight: 500;
    text-align: center;
}
#contactResult.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
#contactResult.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
@media (max-width: 768px) {
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    .contact-hero h1 {
        font-size: 2.2rem;
    }
    .contact-form-card {
        padding: 30px 20px;
    }
}
</style>

<section class="contact-section">
    <div class="contact-hero">
        <h1>📧 Contact Us</h1>
        <p>We'd love to hear from you! Get in touch with any questions or feedback.</p>
    </div>

    <div class="contact-container">
        <div class="contact-grid">
            <!-- Left column: info cards -->
            <div class="info-cards">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Sri Lanka Headquarters</h3>
                    <p>
                        123 Galle Road, Colombo 03<br>
                        Western Province, Sri Lanka
                        <strong><small>📍 GPS: 6.9271° N, 79.8612° E</small></strong>
                    </p>
                </div>

                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3>Contact Numbers</h3>
                    <p>
                        📞 LK Sri Lanka: <strong>+94 11 234-BOOK</strong><br>
                        💬 WhatsApp: <strong>+94 77 123 4567</strong><br>
                        🛠️ Customer Service: <strong>+94 11 789-HELP</strong><br>
                        <strong>🕐 Mon–Sat: 9AM–8PM (Sri Lanka Time)</strong>
                    </p>
                </div>
            </div>

            <!-- Right column: contact form -->
            <div class="contact-form-card">
                <h3><i class="fas fa-paper-plane"></i> Send us a Message</h3>
                <p class="form-subtitle">Fill out the form below and we'll get back to you shortly</p>
                
                <form id="contactForm" method="post" action="backend/api/contact_submit.php">
                    <div class="form-group">
                        <input name="name" type="text" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input name="email" type="email" placeholder="Your Email" required>
                    </div>
                    <div class="form-group">
                        <select name="subject" required>
                            <option value="">Select Subject</option>
                            <option value="order">📦 Order / Shipping</option>
                            <option value="support">🛠️ Support</option>
                            <option value="general">💬 General</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea name="message" placeholder="Your Message" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i>
                        Send Message
                    </button>
                </form>
                <div id="contactResult"></div>
            </div>
        </div>
    </div>
</section>

<script>
// AJAX submit to avoid full page reload
(function(){
    const form = document.getElementById('contactForm');
    const resultDiv = document.getElementById('contactResult');
    if (!form) return;
    form.addEventListener('submit', function(e){
        e.preventDefault();
        const data = new FormData(form);
        fetch(form.action, { method: 'POST', body: data })
            .then(r=>r.json())
            .then(js=>{
                resultDiv.style.display = 'block';
                resultDiv.className = js.success ? 'success' : 'error';
                resultDiv.textContent = js.message || (js.success ? 'Message sent. Thank you!' : 'Failed to send message.');
                if(js.success) form.reset();
            }).catch(err=>{
                resultDiv.style.display = 'block';
                resultDiv.className = 'error';
                resultDiv.textContent = 'An error occurred. Please try again.';
            });
    });
})();
</script>
