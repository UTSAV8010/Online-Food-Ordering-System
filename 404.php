<?php
http_response_code(404);

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$basePath = rtrim($scriptDir, '/');
if ($basePath === '/') {
    $basePath = '';
}

$homeUrl = $basePath . '/';
$menuUrl = $basePath . '/menu.php';
$restroUrl = $basePath . '/restaurant.php';
$contactUrl = $basePath . '/contact.php';
$logoUrl = $basePath . '/images/logo2.png';
$heroUrl = $basePath . '/images/hero.png';
$originalRequest = $_SERVER['REDIRECT_URL'] ?? $_SERVER['REQUEST_URI'] ?? '/';
$requestedPath = parse_url($originalRequest, PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>404 | Pasar-kita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, follow">
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #081127;
            --panel: rgba(8, 20, 46, 0.84);
            --panel-border: rgba(255, 255, 255, 0.12);
            --text: #f4f7ff;
            --muted: #a9b7d1;
            --accent: #f4a40c;
            --accent-deep: #e17d09;
            --chip: rgba(255, 255, 255, 0.08);
            --shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
        }

        body {
            font-family: "Outfit", sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(244, 164, 12, 0.26), transparent 34%),
                radial-gradient(circle at bottom right, rgba(72, 132, 255, 0.22), transparent 30%),
                linear-gradient(135deg, #060d1d 0%, #09152f 48%, #0d1d44 100%);
            overflow-x: hidden;
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            z-index: 0;
            border-radius: 999px;
            filter: blur(16px);
            opacity: 0.55;
            animation: orb-drift 12s ease-in-out infinite;
        }

        body::before {
            width: 340px;
            height: 340px;
            top: -120px;
            left: -60px;
            background: rgba(244, 164, 12, 0.24);
        }

        body::after {
            width: 420px;
            height: 420px;
            right: -160px;
            bottom: -120px;
            background: rgba(53, 122, 255, 0.18);
            animation-delay: -6s;
        }

        .page-wrap {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px;
        }

        .error-shell {
            width: min(1180px, 100%);
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(320px, 0.9fr);
            gap: 24px;
            background: linear-gradient(135deg, rgba(10, 22, 48, 0.96), rgba(8, 17, 39, 0.88));
            border: 1px solid var(--panel-border);
            border-radius: 30px;
            box-shadow: var(--shadow);
            overflow: hidden;
            opacity: 0;
            transform: translateY(22px) scale(0.985);
            animation: shell-in 0.9s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        .content-panel,
        .art-panel {
            position: relative;
            padding: 34px;
        }

        .content-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .content-panel > * {
            opacity: 0;
            transform: translateY(20px);
            animation: content-in 0.75s ease forwards;
        }

        .content-panel > :nth-child(1) { animation-delay: 0.14s; }
        .content-panel > :nth-child(2) { animation-delay: 0.24s; }
        .content-panel > :nth-child(3) { animation-delay: 0.34s; }
        .content-panel > :nth-child(4) { animation-delay: 0.44s; }
        .content-panel > :nth-child(5) { animation-delay: 0.54s; }

        .brand-chip {
            width: fit-content;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-radius: 999px;
            background: var(--chip);
            border: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: #fff4d9;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }

        .brand-chip img {
            width: 34px;
            height: 34px;
            object-fit: contain;
        }

        .error-code {
            margin: 24px 0 8px;
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(5.2rem, 13vw, 9.8rem);
            line-height: 0.9;
            font-weight: 700;
            color: transparent;
            -webkit-text-stroke: 2px rgba(255, 255, 255, 0.82);
            text-shadow: 0 20px 40px rgba(0, 0, 0, 0.24);
            animation:
                content-in 0.75s ease 0.24s forwards,
                code-glow 4.8s ease-in-out 1.05s infinite;
        }

        .headline {
            margin: 0;
            font-size: clamp(1.8rem, 3vw, 3.4rem);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -0.03em;
        }

        .headline span {
            color: var(--accent);
        }

        .summary {
            max-width: 640px;
            margin: 16px 0 0;
            font-size: 1.02rem;
            line-height: 1.7;
            color: var(--muted);
        }

        .path-chip {
            margin-top: 22px;
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #dce6fb;
            font-size: 0.94rem;
            word-break: break-word;
        }

        .path-chip strong {
            color: #ffffff;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 28px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 54px;
            padding: 0 22px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 0.98rem;
            text-decoration: none;
            transition: transform 0.22s ease, box-shadow 0.22s ease, background 0.22s ease, border-color 0.22s ease;
        }

        .btn-primary {
            color: #101827;
            background: linear-gradient(135deg, #ffbf3e, var(--accent));
            box-shadow: 0 16px 34px rgba(244, 164, 12, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 42px rgba(244, 164, 12, 0.34);
        }

        .btn-secondary {
            color: var(--text);
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.09);
        }

        .quick-links {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 30px;
        }

        .quick-link {
            display: block;
            padding: 16px 18px;
            border-radius: 20px;
            text-decoration: none;
            color: inherit;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: transform 0.22s ease, border-color 0.22s ease, background 0.22s ease;
            opacity: 0;
            transform: translateY(18px);
            animation: content-in 0.7s ease forwards;
        }

        .quick-link:nth-child(1) { animation-delay: 0.68s; }
        .quick-link:nth-child(2) { animation-delay: 0.8s; }
        .quick-link:nth-child(3) { animation-delay: 0.92s; }

        .quick-link:hover {
            transform: translateY(-4px);
            border-color: rgba(244, 164, 12, 0.45);
            background: rgba(244, 164, 12, 0.09);
        }

        .quick-link strong {
            display: block;
            font-size: 1rem;
            margin-bottom: 6px;
            color: #ffffff;
        }

        .quick-link span {
            display: block;
            color: var(--muted);
            line-height: 1.5;
            font-size: 0.92rem;
        }

        .art-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at top right, rgba(244, 164, 12, 0.2), transparent 34%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0));
        }

        .art-panel::before,
        .art-panel::after {
            content: "";
            position: absolute;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .art-panel::before {
            width: 170px;
            height: 170px;
            top: 42px;
            right: 32px;
            transform: rotate(14deg);
            animation: panel-card-float-a 8s ease-in-out infinite;
        }

        .art-panel::after {
            width: 120px;
            height: 120px;
            bottom: 46px;
            left: 34px;
            transform: rotate(-12deg);
            animation: panel-card-float-b 9s ease-in-out infinite;
        }

        .visual-card {
            position: relative;
            width: min(430px, 100%);
            padding: 28px 24px 22px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            text-align: center;
            backdrop-filter: blur(10px);
            opacity: 0;
            transform: translateY(28px) scale(0.97);
            animation: visual-in 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.3s forwards;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            font-size: 0.85rem;
            font-weight: 700;
            color: #ffefc9;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: var(--accent);
            box-shadow: 0 0 0 8px rgba(244, 164, 12, 0.14);
            animation: dot-pulse 1.9s ease-in-out infinite;
        }

        .visual-card img {
            width: min(300px, 92%);
            display: block;
            margin: 26px auto 18px;
            filter: drop-shadow(0 22px 40px rgba(0, 0, 0, 0.28));
            transform-origin: center center;
            animation: pizza-spin 18s linear infinite;
        }

        .visual-title {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 800;
        }

        .visual-text {
            margin: 10px auto 0;
            max-width: 300px;
            color: var(--muted);
            line-height: 1.6;
            font-size: 0.95rem;
        }

        @keyframes pizza-spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes shell-in {
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes content-in {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes visual-in {
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes code-glow {
            0%, 100% {
                text-shadow: 0 20px 40px rgba(0, 0, 0, 0.24);
            }
            50% {
                text-shadow: 0 20px 44px rgba(244, 164, 12, 0.18), 0 16px 34px rgba(0, 0, 0, 0.2);
            }
        }

        @keyframes dot-pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(244, 164, 12, 0.34);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(244, 164, 12, 0.08);
                transform: scale(1.12);
            }
        }

        @keyframes panel-card-float-a {
            0%, 100% {
                transform: translateY(0) rotate(14deg);
            }
            50% {
                transform: translateY(-12px) rotate(8deg);
            }
        }

        @keyframes panel-card-float-b {
            0%, 100% {
                transform: translateY(0) rotate(-12deg);
            }
            50% {
                transform: translateY(10px) rotate(-4deg);
            }
        }

        @keyframes orb-drift {
            0%, 100% {
                transform: translate3d(0, 0, 0) scale(1);
            }
            50% {
                transform: translate3d(20px, -18px, 0) scale(1.04);
            }
        }

        @media (max-width: 960px) {
            .error-shell {
                grid-template-columns: 1fr;
            }

            .art-panel {
                padding-top: 0;
            }
        }

        @media (max-width: 640px) {
            .page-wrap {
                padding: 16px;
            }

            .content-panel,
            .art-panel {
                padding: 22px;
            }

            .quick-links {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .visual-card {
                padding: 22px 18px 18px;
            }
        }
    </style>
</head>
<body>
    <main class="page-wrap">
        <section class="error-shell" aria-labelledby="error-title">
            <div class="content-panel">
                <div class="brand-chip">
                    <img src="<?php echo htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="Pasar-kita logo">
                    <span>Pasar-kita Error Page</span>
                </div>

                <div class="error-code">404</div>
                <h1 class="headline" id="error-title">This route took a <span>wrong turn</span>.</h1>
                <p class="summary">
                    The page you tried to open does not exist, may have been moved, or the URL is incorrect.
                    Use one of the quick links below to get back into the site.
                </p>

                <!-- <div class="path-chip">
                    <strong>Requested URL:</strong>
                    <?php echo htmlspecialchars($requestedPath ?: '/', ENT_QUOTES, 'UTF-8'); ?>
                </div> -->

                <div class="actions">
                    <a class="btn btn-primary" href="<?php echo htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8'); ?>">Go To Home</a>
                    <a class="btn btn-secondary" href="<?php echo htmlspecialchars($menuUrl, ENT_QUOTES, 'UTF-8'); ?>">Browse Menu</a>
                </div>

                <div class="quick-links">
                    <a class="quick-link" href="<?php echo htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8'); ?>">
                        <strong>Home</strong>
                        <span>Return to the main landing page and featured sections.</span>
                    </a>
                    <a class="quick-link" href="<?php echo htmlspecialchars($restroUrl, ENT_QUOTES, 'UTF-8'); ?>">
                        <strong>Restaurants</strong>
                        <span>Explore restaurant listings and active food collections.</span>
                    </a>
                    <a class="quick-link" href="<?php echo htmlspecialchars($contactUrl, ENT_QUOTES, 'UTF-8'); ?>">
                        <strong>Contact</strong>
                        <span>Reach out if you followed a broken link or need support.</span>
                    </a>
                </div>
            </div>

            <div class="art-panel" aria-hidden="true">
                <div class="visual-card">
                    <div class="status-pill">
                        <span class="status-dot"></span>
                        <span>Link Not Found</span>
                    </div>
                    <img src="<?php echo htmlspecialchars($heroUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="">
                    <h2 class="visual-title">Beautiful fallback, no layout clutter</h2>
                    <p class="visual-text">
                        This 404 screen loads on wrong URLs only and does not include the site's normal header or footer.
                    </p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
