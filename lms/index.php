<!-- index.php -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Listings</title>
    <link rel="stylesheet" href="styleshomes.css">
    <style>
        .wishlist-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: red;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            z-index: 1000;
        }

        .wishlist-panel {
            position: fixed;
            right: -400px;
            top: 0;
            width: 350px;
            height: 100%;
            background: #fff;
            border-left: 2px solid #ddd;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            transition: right 0.3s ease-in-out;
            z-index: 999;
            overflow-y: auto;
        }

        .wishlist-panel.open {
            right: 0;
        }

        .close-btn {
            margin-top: 20px;
            padding: 10px;
            width: 100%;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .group-card {
    width: 220px;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    text-align: center;
    margin: 10px;
}

.group-card:hover {
    transform: scale(1.03);
}

.group-image-wrapper {
    width: 100%;
    height: 120px;  /* ✅ Make this smaller */
    overflow: hidden;
    background: #f0f0f0;
}

.group-image {
    width: 100%;
    height: 100%;
    object-fit: cover;  /* ✅ Maintain aspect ratio inside box */
    transition: transform 0.3s ease;
}

.group-card:hover .group-image {
    transform: scale(1.05);
}

.group-info {
    padding: 10px;
}

.group-name {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 5px;
}

.group-address,
.group-phone {
    font-size: 13px;
    color: #555;
    margin-bottom: 6px;
}

.group-info button {
    padding: 8px 12px;
    border: none;
    background-color: #007bff;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
}

.group-info button:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
<header class="top-header">
    <div class="logo">
        <img src="LMS.png" alt="LMS logo" class="logo">
    </div>
    <section class="hero">
        <h1>Find Trusted Workers Easily</h1>
        <p>Connecting skilled workers with the right opportunities!</p>
    </section>
    <div class="search-box">
        <form action="search_results.php" method="GET">
            <input type="text" name="search" placeholder="Search listings...">
            <button type="submit">Search</button>
        </form>
    </div>
    <div class="actions">
        
        <div>
            <button class="login" onclick="location.href='profile.php'">Profile</button>
        </div>
        <div class="cell-button-wrapper">
            <button class="cell-button" onclick="location.href='slection.php'">+ Work</button>
        </div>
        <div>
            <button class="login" onclick="location.href='about1.php'">About</button>
        </div>
    </div>
</header>

<div class="cat">
    <h2>Single Workers</h2>
</div>

<div class="listing-container">
    <?php include 'display_workers.php'; ?>
    
</div>
<div class="cat">
    <h2>Worker Groups</h2>
</div>

<div class="listing-container">
    <?php include 'display_groups.php'; ?>
</div>


<!-- Wishlist Panel -->
<div id="wishlistPanel" class="wishlist-panel">
    <h3>❤️ My Wishlist</h3>
    <div id="wishlistContent">
        <p>Loading...</p>
    </div>
    <button onclick="closeWishlist()" class="close-btn">Close</button>
</div>

<!-- Floating Wishlist Button -->
<button onclick="openWishlist()" class="wishlist-toggle">❤️</button>

<script>
    function openWishlist() {
        document.getElementById("wishlistPanel").classList.add("open");
        fetch('wishlist_fetch.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById("wishlistContent").innerHTML = data;
            });
    }

    function closeWishlist() {
        document.getElementById("wishlistPanel").classList.remove("open");
    }

    function toggleWishlist(workerId) {
        fetch('wishlist_toggle.php?worker_id=' + workerId)
            .then(response => response.text())
            .then(data => {
                alert(data);
                openWishlist();
            });
    }
</script>
</body>
</html>
