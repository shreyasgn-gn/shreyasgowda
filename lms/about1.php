<!DOCTYPE html>
<html>
<head>
    <title>About Us</title>
    <style>
        .logo {
    position: absolute;
    top: 15px;
    left: 30px;
    height: 60px;
}
        .about-button-wrapper {
    background: linear-gradient(45deg, #ff6b6b, #f5b942, #58d68d, #5dade2);
    background-size: 300% 300%;
    padding: 3px;
    border-radius: 50px;
    animation: gradientShift 6s ease infinite;
}

.about-button{
    background-color: white;
    color: #d18787;
    border-radius: 50px;
    padding: 10px 20px;
}

.about-button:hover {
    background-color: #eee;
    box-shadow: 0 0 12px rgba(0,0,0,0.2);
}
/* General Content Styling */
.about-content {
    font-family: 'Poppins', sans-serif; /* Modern font */
    padding: 20px;
    margin: auto;
    width: 90%;
    max-width: 1200px;
    background: linear-gradient(to bottom, #ffffff, #f9f9f9); /* Subtle gradient */
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); /* Adds depth */
    animation: fadeSlideIn 1.5s ease-in-out;
}

/* Content Box Style */
.content-box {
    background-color: #ffffff;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 10px;
    border: 1px solid #ddd; /* Light border */
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); /* Depth */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth hover animation */
}
/* Header Styling */
.top-header {
    
 background-image: url('bgofmain.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    text-align: center;
     height: 300px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    color: black;
    padding: 20px;
    border-radius: 10px;
}
/* Animations */
@keyframes fadeSlideIn {
    from {
        opacity: 0;
        transform: translateY(20px); /* Slides up from below */
    }
    to {
        opacity: 1;
        transform: translateY(0); /* Settles into position */
    }
}

@keyframes textFadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
.top-header h1 {
    font-size: 2.5rem;
    font-weight: bold;
    animation: textFadeIn 1.5s ease-out;
}

/* Footer Styling */
.footer {
    margin-top: 20px;
    text-align: center;
    background-color: #4CAF50;
    color: white;
    padding: 10px;
    border-radius: 5px;
}

/* Back Button Styling */
.button-wrapper {
    text-align: center;
    margin-top: 20px;
}

.back-button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    display: inline-flex;
    align-items: center;
    gap: 8px; /* Space between arrow and text */
    transition: background-color 0.3s ease, transform 0.3s ease;
}
        </style>
</head>
<body>
    <header class="top-header">
        <div class="logo">
            <img src="LMS.png" alt="LMS logo" class="logo">
        </div>
        <h1>About Us</h1>
    </header>

    <section class="about-content">
        <!-- Mission Section -->
        <div class="content-box">
            <h2>Our Mission</h2>
            <p>We aim to bridge the gap between skilled workers and employers by creating an efficient, reliable, and user-friendly platform. LMS connects talents with opportunities to foster growth and development.</p>
        </div>

        <!-- Why Choose Us Section -->
        <div class="content-box">
            <h2>Why Choose Us?</h2>
            <ul>
                <li>A large network of reliable professionals.</li>
                <li>A seamless and intuitive user experience.</li>
                <li>Focus on trust and quality relationships.</li>
            </ul>
        </div>

        <!-- How It Works Section -->
        <div class="content-box">
            <h2>How It Works</h2>
            <p>Employers can post job requirements, and workers can find job opportunities. Using advanced search and filter options, users can easily connect with the right people for their needs.</p>
        </div>

        <!-- Vision Section -->
        <div class="content-box">
            <h2>Our Vision</h2>
            <p>To become the go-to platform for labor management and collaboration, empowering individuals and businesses alike.</p>
        </div>

        <!-- Back Button -->
        <div class="button-wrapper">
            <button class="back-button" onclick="location.href='index.php'">&#8592; Back</button>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2025 LMS. Connecting lives, building futures.</p>
    </footer>
</body>
</html>