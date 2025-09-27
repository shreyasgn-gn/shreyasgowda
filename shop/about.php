<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit;
}

$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us – FreshVeg</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      color: #333;
    }
    .container {
      max-width: 900px;
      margin: 60px auto;
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      position: relative;
    }
    h2 {
      color: #2a9d8f;
      text-align: center;
      margin-bottom: 30px;
    }
    p {
      font-size: 1.1em;
      line-height: 1.7;
      margin-bottom: 20px;
    }
    .back-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      background-color: #2a9d8f;
      color: white;
      padding: 8px 16px;
      border: none;
      border-radius: 6px;
      font-size: 0.95em;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.3s ease;
    }
    .back-btn:hover {
      background-color: #21867a;
    }
    footer {
      background-color: #2a9d8f;
      color: white;
      padding: 40px 20px;
      margin-top: 60px;
    }
    footer a {
      color: white;
      text-decoration: none;
    }
    footer ul {
      list-style: none;
      padding: 0;
    }
    footer h4, footer h3 {
      margin-bottom: 10px;
    }
    .footer-content {
      max-width: 1000px;
      margin: auto;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 20px;
    }
    .social-icons {
      text-align: center;
      margin-top: 30px;
    }
    .social-icons a {
      margin: 0 10px;
      font-size: 1.4em;
      text-decoration: none;
    }
    .copyright {
      text-align: center;
      margin-top: 30px;
      font-size: 0.9em;
      color: #e0f7f4;
    }
  </style>
</head>
<body>

<div class="container">
  <a href="user_dashboard.php" class="back-btn">⬅️ Back</a>
  <h2>🌱 Welcome to FreshVeg</h2>
  <p>
    At <strong>FreshVeg</strong>, we believe that fresh, healthy food should be accessible to everyone—without the hassle. We're a local vegetable shop built for real people, with real needs. Whether you're cooking for your family, stocking up for the week, or just craving something green and crisp, we've got you covered.
  </p>
  <p>
    Our inventory is updated daily, sourced directly from trusted farmers and local markets. We focus on quality, transparency, and simplicity. No confusing prices. No hidden charges. Just clean, honest vegetables delivered with care.
  </p>
  <p>
    What makes us different? We listen. Our platform lets you request specific items, upload handwritten lists, and even ask our AI assistant about availability. We're not just a shop—we're your kitchen companion.
  </p>
  <p>
    Thank you for choosing FreshVeg. We’re proud to serve you, and we’re always improving to make your experience smoother, smarter, and more satisfying.
  </p>
  <div style="text-align:center; margin-top:30px;">
    <span style="font-size:1.2em; color:#2a9d8f;">🥕 Fresh. Local. Reliable.</span><br>
    <span style="font-size:1em; color:#777;">— The FreshVeg Team</span>
  </div>
</div>

<footer>
  <div class="footer-content">
    <div style="flex:1; min-width:250px;">
      <h3>🥬 FreshVeg</h3>
      <p>
        Your trusted local vegetable shop. We deliver fresh, affordable produce with care and simplicity. Built for real kitchens, powered by smart tech.
      </p>
    </div>
    <div style="flex:1; min-width:200px;">
      <h4>🔗 Quick Links</h4>
      <ul>
        <li><a href="user_dashboard.php">🏠 Home</a></li>
        <li><a href="profile.php">👤 Profile</a></li>
        <li><a href="about.php">ℹ️ About Us</a></li>
      
      </ul>
    </div>
    <div style="flex:1; min-width:250px;">
      <h4>📞 Contact</h4>
      <p>
        Bengaluru South, Karnataka<br>
        Phone:*********<br>
        Email: <a href="mailto:support@freshveg.in">support@freshveg.in</a>
      </p>
    </div>
  </div>

 

  <div class="copyright">
    © <?= date('Y') ?> FreshVeg. All rights reserved.
  </div>
</footer>

</body>
</html>