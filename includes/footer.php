<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smart Municipality Footer</title>
<link rel="stylesheet" href="/Municipality/css/material-symbols.css">
<link rel="stylesheet" href="/Municipality/css/style.css">
<style>
  /* Fonts & Body */
  body {
    margin: 0;
    font-family: 'Public Sans', 'Noto Sans', sans-serif;
    background-color: #ffffff;
  }

  /* Footer Container */
  footer {
    background-color: #ffffff; /* light mode */
    border-top: 1px solid #e7ecf3;
    padding: 3rem 1rem; /* py-12 px-4 */
    color: #0d131b; /* text-main */
  }

  /* Dark mode */
  body.dark footer {
    background-color: #15202b;
    border-color: #1f2a38;
    color: #ffffff;
  }

  /* Inner container */
  .footer-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem; /* gap-6 */
  }

  @media (min-width: 768px) {
    .footer-container {
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
    }
  }

  /* Logo + Name */
  .footer-logo {
    display: flex;
    align-items: center;
    gap: 0.5rem; /* gap-2 */
    font-weight: bold;
  }

  .footer-logo .material-symbols-outlined {
    color: #136dec; /* primary */
    font-size: 1.25rem; /* roughly text-xl */
  }

  body.dark .footer-logo .material-symbols-outlined {
    color: #136dec; /* keep primary color same in dark mode */
  }

  /* Links */
  .footer-links {
    display: flex;
    gap: 2rem; /* gap-8 */
    font-size: 0.875rem; /* text-sm */
    color: #4c6c9a; /* text-text-muted */
  }

  .footer-links a {
    text-decoration: none;
    transition: color 0.2s;
    color: inherit;
  }

  .footer-links a:hover {
    color: #136dec; /* primary */
  }

  /* Dark mode links */
  body.dark .footer-links {
    color: #b0b8c1; /* lighter muted for dark mode */
  }

  /* Copyright */
  .footer-copy {
    font-size: 0.75rem; /* text-xs */
    color: #4c6c9a; /* text-text-muted */
    text-align: center;
  }

  body.dark .footer-copy {
    color: #b0b8c1; /* muted for dark mode */
  }
</style>
</head>
<body class="light">
<footer>
  <div class="footer-container">
    <!-- Logo and Name -->
    <div class="footer-logo">
      <span class="material-symbols-outlined">account_balance</span>
      <span>Smart Municipality</span>
    </div>

    <!-- Links -->
    <div class="footer-links">
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
      <a href="#">Emergency Contacts</a>
    </div>

    <!-- Copyright -->
    <p class="footer-copy">© 2024 Smart Municipality. All rights reserved.</p>
  </div>
</footer>
</body>
</html>
