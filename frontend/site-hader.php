<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$cartCount = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

$isHome = $currentPage === 'index.php';
$isRestro = in_array($currentPage, array('restaurant.php', 'restro-category.php', 'restro-menu.php'));
$isAbout = $currentPage === 'about.php';
$isCategories = in_array($currentPage, array('categories.php', 'category-foods.php'));
$isMenu = in_array($currentPage, array('menu.php', 'add-to-cart.php', 'mycart.php'));
$isPages = in_array($currentPage, array('team.php', 'testimonial.php', 'myaccount.php', 'update-account.php', 'update-password.php', 'view-orders.php', 'review-restro.php', 'review-rider.php'));
$isContact = in_array($currentPage, array('contact.php', 'message.php'));
$needsTopOffset = !$isHome;
$hideBreadcrumb = in_array($currentPage, array('login.php', 'signup.php', 'forget.php'));
$loginTarget = isset($_SESSION['user']) ? 'myaccount.php' : 'login.php';
$restroTarget = 'restaurant.php';

$pageTitles = array(
    'about.php' => 'About',
    'add-to-cart.php' => 'Add To Cart',
    'categories.php' => 'Categories',
    'category-foods.php' => 'Category Foods',
    'contact.php' => 'Contact',
    'festival.php' => 'Festival Offer',
    'forget.php' => 'Forgot Password',
    'login.php' => 'Login',
    'menu.php' => 'Menu',
    'myaccount.php' => 'My Account',
    'mycart.php' => 'My Cart',
    'restaurant.php' => 'Restro',
    'restro-category.php' => 'Restro Category',
    'restro-menu.php' => 'Restro Menu',
    'review-restro.php' => 'Review Restro',
    'review-rider.php' => 'Review Rider',
    'section.php' => 'Our Chefs',
    'signup.php' => 'Sign Up',
    'team.php' => 'Team',
    'testimonial.php' => 'Testimonial',
    'update-account.php' => 'Update Account',
    'update-password.php' => 'Update Password',
    'view-orders.php' => 'View Orders'
);

$currentTitle = isset($pageTitles[$currentPage])
    ? $pageTitles[$currentPage]
    : ucwords(str_replace(array('.php', '-'), array('', ' '), $currentPage));

$breadcrumbGroups = array(
    'about.php' => array('label' => 'About', 'url' => 'about.php'),
    'add-to-cart.php' => array('label' => 'Menu', 'url' => 'menu.php'),
    'categories.php' => array('label' => 'Categories', 'url' => 'categories.php'),
    'category-foods.php' => array('label' => 'Categories', 'url' => 'categories.php'),
    'contact.php' => array('label' => 'Contact', 'url' => 'contact.php'),
    'festival.php' => array('label' => 'Offers', 'url' => 'festival.php'),
    'forget.php' => array('label' => 'Account', 'url' => 'login.php'),
    'login.php' => array('label' => 'Account', 'url' => 'login.php'),
    'menu.php' => array('label' => 'Menu', 'url' => 'menu.php'),
    'myaccount.php' => array('label' => 'Pages', 'url' => 'myaccount.php'),
    'mycart.php' => array('label' => 'Menu', 'url' => 'menu.php'),
    'restaurant.php' => array('label' => 'Restro', 'url' => 'restaurant.php'),
    'restro-category.php' => array('label' => 'Restro', 'url' => 'restaurant.php'),
    'restro-menu.php' => array('label' => 'Restro', 'url' => 'restaurant.php'),
    'review-restro.php' => array('label' => 'Pages', 'url' => 'view-orders.php'),
    'review-rider.php' => array('label' => 'Pages', 'url' => 'view-orders.php'),
    'section.php' => array('label' => 'Pages', 'url' => 'section.php'),
    'signup.php' => array('label' => 'Account', 'url' => 'signup.php'),
    'team.php' => array('label' => 'Pages', 'url' => 'team.php'),
    'testimonial.php' => array('label' => 'Pages', 'url' => 'testimonial.php'),
    'update-account.php' => array('label' => 'Pages', 'url' => 'myaccount.php'),
    'update-password.php' => array('label' => 'Pages', 'url' => 'myaccount.php'),
    'view-orders.php' => array('label' => 'Pages', 'url' => 'view-orders.php')
);

$breadcrumbGroup = isset($breadcrumbGroups[$currentPage])
    ? $breadcrumbGroups[$currentPage]
    : array('label' => 'Pages', 'url' => 'index.php');
?>
<style>
html,
body {
    margin: 0 !important;
    padding: 0 !important;
}

.site-header-wrap {
    position: fixed !important;
    top: 0;
    left: 0;
    right: 0;
    width: 100%;
    z-index: 1080;
    margin: 0 !important;
    padding: 0 !important;
    background: #11192b;
}

.site-header {
    position: relative !important;
    top: 0 !important;
    left: 0 !important;
    width: 100%;
    margin: 0 !important;
    background: #11192b;
    min-height: 86px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
}

.container-xxl.bg-white.p-0 {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

<?php if ($needsTopOffset) { ?>
body {
    padding-top: 92px !important;
}
<?php } ?>

.site-breadcrumb-wrap {
    background: linear-gradient(90deg, rgba(13, 23, 48, 0.98), rgba(8, 18, 42, 0.98));
<?php if ($needsTopOffset) { ?>
    margin-top: -92px;
    padding-top: 92px;
<?php } ?>
}

.site-breadcrumb-wrap .container {
    padding-top: 60px;
    padding-bottom: 60px;
    text-align: center;
}

.site-breadcrumb-title {
    margin: 0 0 8px 0;
    font-size: clamp(2rem, 4vw, 4rem);
    font-weight: 800;
    line-height: 1.1;
    color: #ffffff;
}

.site-breadcrumb-path {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.site-breadcrumb-path a {
    color: #FEA116;
    text-decoration: none;
}

.site-breadcrumb-path .sep {
    color: #ffffff;
    padding: 0 8px;
}

.site-breadcrumb-path .current {
    color: #ffffff;
}

.site-header .navbar-brand img {
    max-height: 100px;
    width: auto;
}

.site-header .navbar-toggler {
    border: 1px solid rgba(255, 255, 255, 0.35) !important;
    border-radius: 10px;
    width: 46px;
    height: 46px;
    padding: 0;
    display: none;
    align-items: center;
    justify-content: center;
}

.site-header .navbar-toggler:focus {
    box-shadow: none;
}

.site-header .toggle-lines {
    display: inline-flex;
    flex-direction: column;
    gap: 5px;
}

.site-header .toggle-lines span {
    width: 20px;
    height: 2px;
    background: #ffffff;
    border-radius: 999px;
    transition: all 0.25s ease;
}

.site-header .navbar-toggler[aria-expanded="true"] .toggle-lines span:nth-child(1) {
    transform: translateY(7px) rotate(45deg);
}

.site-header .navbar-toggler[aria-expanded="true"] .toggle-lines span:nth-child(2) {
    opacity: 0;
}

.site-header .navbar-toggler[aria-expanded="true"] .toggle-lines span:nth-child(3) {
    transform: translateY(-7px) rotate(-45deg);
}

.mobile-drawer-head {
    display: none;
}

.site-header .navbar-nav .nav-link {
    font-weight: 700;
    color: #ffffff;
    padding: 24px 14px !important;
    margin: 0 !important;
    line-height: 1.2;
}

@media (min-width: 992px) {
    .site-header .navbar-nav {
        gap: 4px;
    }

    .site-header.sticky-top .navbar-nav .nav-link,
    .sticky-top .site-header .navbar-nav .nav-link,
    .sticky-top.site-header .navbar-nav .nav-link {
        padding: 24px 14px !important;
    }

    .site-header .nav-item.dropdown {
        position: relative;
    }

    .site-header .navbar-nav .dropdown-menu {
        /* margin-top: 10px !important; */
        min-width: 220px;
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 14px;
        background: #11192b;
        box-shadow: 0 16px 34px rgba(0, 0, 0, 0.35);
        padding: 8px;
    }

    .site-header .navbar-nav .dropdown-item {
        color: #dfe9ff;
        border-radius: 10px;
        padding: 10px 12px;
        margin-bottom: 4px;
        transition: all 0.2s ease;
    }

    .site-header .navbar-nav .dropdown-item:last-child {
        margin-bottom: 0;
    }

    .site-header .navbar-nav .dropdown-item:hover,
    .site-header .navbar-nav .dropdown-item:focus {
        color: #FEA116 ;
        background: transparent;
    }
}

.site-header .navbar-nav .nav-link:hover,
.site-header .navbar-nav .nav-link.active,
.site-header .navbar-nav .show > .nav-link {
    color: #FEA116 !important;
}

.site-header .navbar-nav .dropdown-item {
    font-family: inherit;
    font-weight: 700;
    font-size: 1rem;
    line-height: 1.2;
}

.site-header .header-actions {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-left: 14px;
}

.site-header .header-icon-btn {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    border: 0;
    background: #e69500;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    position: relative;
    text-decoration: none;
}

.site-header .header-icon-btn:hover {
    background: #ffab00;
    color: #fff;
}

.site-header .header-icon-btn svg {
    width: 20px;
    height: 20px;
    stroke: currentColor;
}

.site-header .cart-badge {
    position: absolute;
    top: -7px;
    right: -7px;
    min-width: 18px;
    height: 18px;
    padding: 0 4px;
    border-radius: 999px;
    background: #ff3b30;
    color: #fff;
    font-size: 11px;
    line-height: 18px;
    text-align: center;
    font-weight: 700;
}

@media (max-width: 991.98px) {
    :root {
        --site-drawer-width: min(310px, 84vw);
    }

    .site-header {
        min-height: auto;
        box-shadow: none;
    }

    .site-header .navbar-toggler {
        display: inline-flex;
    }

    .site-header .navbar-nav .nav-link {
        padding: 10px 0 !important;
    }

    .site-header .navbar-collapse {
        position: fixed !important;
        top: 86px;
        left: 0;
        width: var(--site-drawer-width);
        max-width: var(--site-drawer-width);
        height: calc(100vh - 86px);
        overflow-y: auto;
        background: #06133b !important;
        padding: 12px !important;
        border-right: 0;
        transform: translateX(-100%);
        transition: transform 0.28s ease;
        z-index: 1095;
        display: block !important;
        box-shadow: none;
    }

    .site-header .navbar-collapse.show {
        transform: translateX(0);
    }

    .site-header .navbar-nav {
        gap: 10px;
        padding-right: 0 !important;
    }

    .site-header .navbar-nav .nav-link {
        background: #103c72;
        border-radius: 14px;
        padding: 12px 16px !important;
    }

    .mobile-drawer-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 6px 4px 12px;
        margin-bottom: 6px;
    }

    .mobile-drawer-logo img {
        width: auto;
        height: 66px;
    }

    .mobile-drawer-close {
        border: 1px solid rgba(255, 255, 255, 0.35);
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        font-size: 20px;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .site-header .navbar-nav .dropdown-menu {
        position: static !important;
        float: none !important;
        margin-top: 8px !important;
        border: 0;
        border-radius: 12px;
        background: #11192b;
        padding: 6px;
    }

    .site-header .nav-item.dropdown .dropdown-menu {
        display: none;
    }

    .site-header .nav-item.dropdown.show-submenu .dropdown-menu {
        display: block;
    }

    .site-header .navbar-nav .dropdown-item {
        color: #fff;
        border-radius: 8px;
        padding: 10px 12px;
    }

    .site-header .navbar-nav .dropdown-item:hover {
        color: #FEA116;
        background: transparent;
    }

    .site-header .header-actions {
        margin: 12px 0 0;
        width: 100%;
        justify-content: space-between;
    }

    .site-header .header-icon-btn {
        width: calc(50% - 6px);
        border-radius: 12px;
    }

    <?php if ($needsTopOffset) { ?>
    body {
        padding-top: 84px !important;
    }

    .site-breadcrumb-wrap {
        margin-top: -84px;
        padding-top: 84px;
    }
    <?php } ?>

    .site-breadcrumb-wrap .container {
        padding-top: 40px;
        padding-bottom: 40px;
    }

    .site-breadcrumb-path {
        font-size: 0.8rem;
    }

    .site-nav-backdrop {
        inset: 86px 0 0 var(--site-drawer-width);
    }
}

.site-nav-backdrop {
    position: fixed;
    inset: 86px 0 0 0;
    background: rgba(0, 0, 0, 0.2);
    z-index: 1070;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity 0.2s ease, visibility 0.2s ease;
}

.site-nav-backdrop.show {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

body.mobile-nav-open {
    overflow: hidden;
}

@media (min-width: 992px) {
    .site-nav-backdrop {
        display: none !important;
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none !important;
    }
}
</style>

<div class="site-header-wrap">
    <nav class="navbar navbar-expand-lg navbar-dark site-header px-4 px-lg-5 py-2 py-lg-0">
        <a href="index.php" class="navbar-brand p-0">
            <img src="images/logo2.png" alt="Logo">
        </a>
        <button id="siteNavToggler" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="toggle-lines" aria-hidden="true"><span></span><span></span><span></span></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <div class="mobile-drawer-head">
                <a href="index.php" class="mobile-drawer-logo">
                    <img src="images/logo2.png" alt="Logo">
                </a>
                <button type="button" id="siteNavClose" class="mobile-drawer-close" aria-label="Close menu">&times;</button>
            </div>
            <div class="navbar-nav ms-auto py-0 pe-3">
                <a href="index.php" class="nav-item nav-link <?php echo $isHome ? 'active' : ''; ?>">Home</a>
                <a href="<?php echo htmlspecialchars($restroTarget); ?>" class="nav-item nav-link <?php echo $isRestro ? 'active' : ''; ?>">Restro</a>
                <a href="about.php" class="nav-item nav-link <?php echo $isAbout ? 'active' : ''; ?>">About</a>
                <a href="categories.php" class="nav-item nav-link <?php echo $isCategories ? 'active' : ''; ?>">Categories</a>
                <a href="menu.php" class="nav-item nav-link <?php echo $isMenu ? 'active' : ''; ?>">Menu</a>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle <?php echo $isPages ? 'active' : ''; ?>" data-bs-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu m-0">
                        <a href="team.php" class="dropdown-item">Team</a>
                        <a href="testimonial.php" class="dropdown-item">Testimonial</a>
                        <a href="myaccount.php" class="dropdown-item">My Account</a>
                        <a href="view-orders.php" class="dropdown-item">My Orders</a>
                        <?php if (isset($_SESSION['user'])) { ?>
                        <a href="logout" class="dropdown-item">Logout</a>
                        <?php } ?>
                    </div>
                </div>

                <a href="contact.php" class="nav-item nav-link <?php echo $isContact ? 'active' : ''; ?>">Contact</a>
            </div>

            <div class="header-actions">
                <a href="mycart.php" class="header-icon-btn" aria-label="Open cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="9" cy="20" r="1"></circle>
                        <circle cx="18" cy="20" r="1"></circle>
                        <path d="M3 4h2l2.2 10.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L21 7H7"></path>
                    </svg>
                    <?php if ($cartCount > 0) { ?><span class="cart-badge"><?php echo $cartCount > 99 ? '99+' : $cartCount; ?></span><?php } ?>
                </a>
                <a href="<?php echo htmlspecialchars($loginTarget); ?>" class="header-icon-btn" aria-label="Login">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 21a8 8 0 0 0-16 0"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </a>
            </div>
        </div>
    </nav>
</div>
<div id="siteNavBackdrop" class="site-nav-backdrop"></div>

<?php if (!$isHome && !$hideBreadcrumb) { ?>
<div class="site-breadcrumb-wrap">
    <div class="container">
        <h2 class="site-breadcrumb-title"><?php echo htmlspecialchars($currentTitle); ?></h2>
        <p class="site-breadcrumb-path">
            <a href="index.php">Home</a>
            <span class="sep">/</span>
            <a href="<?php echo htmlspecialchars($breadcrumbGroup['url']); ?>"><?php echo htmlspecialchars($breadcrumbGroup['label']); ?></a>
            <span class="sep">/</span>
            <span class="current"><?php echo htmlspecialchars($currentTitle); ?></span>
        </p>
    </div>
</div>
<?php } ?>
<script>
(function () {
    var toggler = document.getElementById('siteNavToggler');
    var menu = document.getElementById('mainNavbar');
    var backdrop = document.getElementById('siteNavBackdrop');
    var closeBtn = document.getElementById('siteNavClose');
    if (!toggler || !menu || !backdrop) return;
    var dropdownItem = menu.querySelector('.nav-item.dropdown');
    var dropdownToggle = menu.querySelector('.nav-item.dropdown > .dropdown-toggle');
    var dropdownMenu = menu.querySelector('.nav-item.dropdown > .dropdown-menu');

    function setOpenState(isOpen) {
        menu.classList.toggle('show', isOpen);
        backdrop.classList.toggle('show', isOpen);
        toggler.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        document.body.classList.toggle('mobile-nav-open', isOpen);
        if (!isOpen && dropdownItem && dropdownMenu) {
            dropdownItem.classList.remove('show-submenu');
            dropdownMenu.classList.remove('show');
            if (dropdownToggle) dropdownToggle.setAttribute('aria-expanded', 'false');
        }
    }

    toggler.addEventListener('click', function (e) {
        e.preventDefault();
        var isOpen = menu.classList.contains('show');
        setOpenState(!isOpen);
    });

    backdrop.addEventListener('click', function () {
        setOpenState(false);
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            setOpenState(false);
        });
    }

    if (dropdownToggle && dropdownItem && dropdownMenu) {
        dropdownToggle.addEventListener('click', function (e) {
            if (window.innerWidth <= 991) {
                e.preventDefault();
                var open = dropdownItem.classList.contains('show-submenu');
                dropdownItem.classList.toggle('show-submenu', !open);
                dropdownMenu.classList.toggle('show', !open);
                dropdownToggle.setAttribute('aria-expanded', !open ? 'true' : 'false');
            }
        });
    }

    menu.querySelectorAll('.nav-link:not(.dropdown-toggle), .dropdown-item, .header-icon-btn').forEach(function (el) {
        el.addEventListener('click', function () {
            if (window.innerWidth <= 991) setOpenState(false);
        });
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 991) setOpenState(false);
    });
})();
</script>
