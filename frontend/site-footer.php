<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<style>
.site-footer {
    background: #0b1533;
    color: #ffffff;
    margin-top: 48px;
    overflow-x: hidden;
}

.site-footer .footer-wrap {
    padding: 56px 0 18px;
}

.site-footer .footer-title {
    color: #fea116;
    font-family: "Pacifico", cursive;
    font-size: 1.75rem;
    font-weight:500;
    margin-bottom: 20px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.site-footer .footer-title::after {
    content: "";
    width: 48px;
    height: 2px;
    background: #fea116;
}

.site-footer a {
    color: #ffffff;
    text-decoration: none;
}

.site-footer .company-links a {
    display: block;
    margin-bottom: 10px;
    font-size: 1.02rem;
    font-weight: 600;
    transition: color 0.2s ease, transform 0.2s ease;
}

.site-footer .company-links a::before {
    content: "\203A";
    margin-right: 10px;
    color: #ffffff;
}

.site-footer .contact-item,
.site-footer .opening-item,
.site-footer .newsletter-text {
    font-size: 1.02rem;
    line-height: 1.55;
    margin-bottom: 10px;
}

.site-footer .social {
    display: flex;
    gap: 10px;
    margin-top: 14px;
}

.site-footer .social a {
    width: 40px;
    height: 40px;
    border: 1px solid rgba(255, 255, 255, 0.8);
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
    transition: all 0.2s ease;
}

.site-footer .social a svg {
    width: 17px;
    height: 17px;
    fill: currentColor;
}

.site-footer .newsletter-box {
    position: relative;
    max-width: 360px;
}

.site-footer .newsletter-box input {
    width: 100%;
    height: 54px;
    border: 1px solid #fea116;
    border-radius: 0;
    padding: 0 120px 0 18px;
    font-size: 1rem;
}

.site-footer .newsletter-box button {
    position: absolute;
    top: 6px;
    right: 6px;
    border: 0;
    background: #e69500;
    color: #fff;
    border-radius: 999px;
    font-weight: 700;
    height: 42px;
    padding: 0 18px;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.site-footer .footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.15);
    padding-top: 16px;
    margin-top: 28px;
    font-size: 1rem;
}

.site-footer .footer-menu {
    display: inline-flex;
    gap: 16px;
    flex-wrap: wrap;
}

.site-footer .footer-menu a,
.site-footer .footer-bottom a {
    transition: color 0.2s ease;
}

.site-footer .company-links a:hover,
.site-footer .footer-menu a:hover,
.site-footer .footer-bottom a:hover {
    color: #fea116;
}

.site-footer .company-links a:hover {
    transform: translateX(4px);
}

.site-footer .social a:hover {
    background: #fea116;
    border-color: #fea116;
    color: #0b1533;
}

.site-footer .newsletter-box button:hover {
    background: #ffb226;
}

@media (max-width: 991.98px) {
    .site-footer .footer-title {
        font-size: 1rem;
    }

    .site-footer .company-links a,
    .site-footer .contact-item,
    .site-footer .opening-item,
    .site-footer .newsletter-text,
    .site-footer .footer-bottom {
        font-size: 0.98rem;
    }

    .site-footer .newsletter-box input {
        height: 48px;
        font-size: 1rem;
    }
}

@media (max-width: 575.98px) {
    .site-footer .container {
        padding-left: 30px;
        padding-right: 30px;
    }

    .site-footer {
        margin-top: 36px;
    }
/* 
    .site-footer .footer-wrap {
        padding: 36px 0 16px;
    } */

    .site-footer .row {
        row-gap: 26px !important;
    }

    .site-footer .footer-title {
        font-size: 1.75rem;
        margin-bottom: 14px;
        font-weight:500;
    }

    .site-footer .footer-title::after {
        width: 44px;
    }

    .site-footer .company-links a,
    .site-footer .contact-item,
    .site-footer .opening-item,
    .site-footer .newsletter-text {
        font-size: 0.98rem;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .site-footer .contact-item i {
        width: 20px;
        text-align: center;
    }

    .site-footer .social {
        margin-top: 10px;
        gap: 8px;
    }

    .site-footer .social a {
        width: 36px;
        height: 36px;
        font-size: 0.95rem;
    }

    .site-footer .newsletter-box {
        max-width: 100%;
    }

    .site-footer .newsletter-box input {
        height: 46px;
        padding: 0 106px 0 14px;
        font-size: 0.96rem;
    }

    .site-footer .newsletter-box button {
        top: 5px;
        right: 5px;
        height: 36px;
        padding: 0 14px;
        font-size: 0.85rem;
    }

    .site-footer .footer-bottom {
        margin-top: 24px;
        padding-top: 14px;
        padding-bottom: 26px;
        text-align: center;
        font-size: 0.92rem;
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
    }

    .site-footer .footer-bottom > div {
        width: 100%;
        text-align: center;
        line-height: 1.5;
        white-space: normal;
    }

    .site-footer .footer-menu {
        width: 100%;
        justify-content: center;
        gap: 12px;
    }
}
</style>

<div class="site-footer">
    <div class="container footer-wrap">
        <div class="row g-5">
            <div class="col-lg-3 col-md-6">
                <h4 class="footer-title">Company</h4>
                <div class="company-links">
                    <a href="about.php">About Us</a>
                    <a href="contact.php">Contact Us</a>
                    <a href="#">Reservation</a>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms &amp; Condition</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="footer-title">Contact</h4>
                <div class="contact-item"><i class="fa fa-map-marker-alt me-2"></i> surat,ahemdabad,baroda</div>
                <div class="contact-item"><i class="fa fa-phone-alt me-2"></i> 9978043407</div>
                <div class="contact-item"><i class="fa fa-envelope me-2"></i> Pasar-kita@gmail.com</div>
                <div class="social">
                    <a href="#" aria-label="Twitter">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2H21.5l-7.11 8.13L22.75 22h-6.55l-5.13-6.73L5.18 22H1.92l7.61-8.7L1.25 2h6.72l4.64 6.13L18.244 2zm-1.15 18h1.81L6.98 3.9H5.03L17.094 20z"/></svg>
                    </a>
                    <a href="#" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 22v-8h2.7l.4-3h-3.1V9.1c0-.87.25-1.46 1.5-1.46h1.65V4.95C16.05 4.87 15.28 4.8 14.4 4.8c-2.58 0-4.35 1.58-4.35 4.48V11H7.1v3h2.95v8h3.45z"/></svg>
                    </a>
                    <a href="#" aria-label="YouTube">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M23 12s0-3.5-.45-5.19a2.9 2.9 0 0 0-2.04-2.05C18.8 4.3 12 4.3 12 4.3s-6.8 0-8.51.46A2.9 2.9 0 0 0 1.45 6.8C1 8.5 1 12 1 12s0 3.5.45 5.19a2.9 2.9 0 0 0 2.04 2.05C5.2 19.7 12 19.7 12 19.7s6.8 0 8.51-.46a2.9 2.9 0 0 0 2.04-2.05C23 15.5 23 12 23 12zM9.8 15.5V8.5l6.2 3.5-6.2 3.5z"/></svg>
                    </a>
                    <a href="#" aria-label="LinkedIn">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM3 9.5h4v11H3v-11zm7 0h3.84V11h.06c.54-1 1.86-2.06 3.83-2.06 4.1 0 4.86 2.7 4.86 6.21v5.35h-4v-4.74c0-1.13-.02-2.57-1.57-2.57-1.57 0-1.81 1.22-1.81 2.49v4.82h-4v-11z"/></svg>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="footer-title">Opening</h4>
                <div class="opening-item">Monday - Saturday<br>09AM - 09PM</div>
                <div class="opening-item">Sunday<br>10AM - 08PM</div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="footer-title">Newsletter</h4>
                <p class="newsletter-text">Dolor amet sit justo amet elitr clita ipsum elitr est.</p>
                <div class="newsletter-box">
                    <input type="text" placeholder="Your email">
                    <button type="button">SIGNUP</button>
                </div>
            </div>
        </div>
        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div>&copy; <a href="#">Pasar.kita.com</a>, All Right Reserved.</div>
            <div class="footer-menu">
                <a href="index.php">Home</a>
                <a href="#">Cookies</a>
                <a href="#">Help</a>
                <a href="#">FQAs</a>
            </div>
        </div>
    </div>
</div>
