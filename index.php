
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="computer.css">
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Restraunt</title>
    <link rel="stylesheet" href="style.css"/>
    <script src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .checked {
            color: gold;
        }
    
        .profile-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    display: none; 
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.profile-modal.active {
    display: flex;
}


.profile-container {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 600px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    text-align: left;
    position: relative;
    animation: slideIn 0.5s ease;
}

@keyframes slideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.profile-container h2 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: #333;
}

.profile-container p {
    margin: 5px 0;
    font-size: 1rem;
    color: #555;
}

.profile-container table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.profile-container table th, .profile-container table td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: left;
}

.profile-container table th {
    background-color: #f4f4f4;
}

.close-button {
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #d9534f;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
}

.close-button:hover {
    background-color: #c9302c;
}

.main-content.blurred {
    filter: blur(4px);
}


.user-profile-modal.active {
            display: block;
        }

        /* Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        .overlay.active {
            display: block;
        }

        
        .close-btn {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }
        .close-btn:hover {
            background-color: #c9302c;
        }


        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }

    </style>
    
</head>

<body>

<?php
session_start();
include 'db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
 if (isset($_SESSION['login_success'])): ?>
    <div class="success-message">
        <?php echo htmlspecialchars($_SESSION['login_success']); ?>
    </div>
    <?php unset($_SESSION['login_success']); ?>
<?php endif; 


$user_id = $_SESSION['user_id'];

$sql_orders = "SELECT item_name, quantity, total_price, address, created_at FROM orders WHERE user_id = ?";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();

$order_history = [];
while ($row = $result_orders->fetch_assoc()) {
    $order_history[] = $row;
}
?>
<?php if (isset($_SESSION['confirmation_message'])): ?>
    <div id="successMessage" style="background-color: #d4edda; color: #155724; padding: 10px; margin: 10px; border: 1px solid #c3e6cb; border-radius: 5px; transition: opacity 0.5s ease;">
        <?php echo htmlspecialchars($_SESSION['confirmation_message']); ?>
    </div>
    <?php unset($_SESSION['confirmation_message']); ?>
<?php endif; ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const successMessage = document.getElementById('successMessage');

        if (successMessage) {
        
            setTimeout(function () {
                if (successMessage) {
                    successMessage.style.opacity = "0"; 
                    setTimeout(() => successMessage.remove(), 400); 
                }
            }, 3000);

            let scrollTimeout;
            window.addEventListener('scroll', function () {
                if (scrollTimeout) clearTimeout(scrollTimeout);

                scrollTimeout = setTimeout(function () {
                    if (successMessage) {
                        successMessage.style.opacity = "0"; 
                        setTimeout(() => successMessage.remove(), 400); 
                    }
                }, 400);
            });
        }
    });
</script>
<?php if(isset($_SESSION['login_success'])) : ?>
        <div style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); background-color: #4CAF50; color: white; padding: 10px 20px; border-radius: 5px; display: none;">
            <?php echo $_SESSION['login_success']; ?>
        </div>
        <script>
            setTimeout(function() {
                document.querySelector('div').style.display = 'none'; 
            }, 2000); 
        </script>
    <?php endif; ?>
    <nav class="navbar">
        <div class="navbar-container container">
            <input type="checkbox" name="" id="">
            <div class="hamburger-lines">   
                <span class="line line1"></span>
                <span class="line line2"></span>
                <span class="line line3"></span>
            </div>
            <ul class="menu-items">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#food">Menu</a></li>
                <li><a href="#testimonials">Testimonial</a></li>
                <li><a href="#contact">Contact</a></li>
                
                <?php if (!isset($_SESSION['user_id'])): ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php else: ?>
            <li><a href="logout.php">Logout</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_id'])): ?>
        <li><a id="toggleProfile">Profile</a></li>
    <?php endif; ?>

   
 </div>
</div>
</div>
</nav>
<?php if (isset($_SESSION['user_id'])): ?><br><br>
          
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="overlay" id="overlay"></div>

        <div id="userProfileModal" class="profile-modal">
    <div class="profile-container">
        <h2>Profile</h2>
        <p><strong>Username:</strong> 
    <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Not Set'; ?>
</p>
<p><strong>Email:</strong> 
    <?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : 'Not Set'; ?>
</p>
<p><strong>Phone:</strong> 
    <?php echo isset($_SESSION['user_phone']) ? htmlspecialchars($_SESSION['user_phone']) : 'Not Set'; ?>
</p>

        
        <h3>Your Orders</h3>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price ($)</th>
                    <th>Address</th>
                    <th>Date</th>
                    <th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_history as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['item_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($order['total_price']); ?></td>
                    <td><?php echo htmlspecialchars($order['address']); ?></td>
                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button id="closeProfile" class="close-button">Close</button>

    </div>
</div>

    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleProfile = document.getElementById('toggleProfile');
            const profileModal = document.getElementById('userProfileModal');
            const closeProfile = document.getElementById('closeProfile');
            const mainContent = document.querySelector('.main-content');

          
            toggleProfile.addEventListener('click', function () {
                profileModal.classList.add('active');
                mainContent.classList.add('blurred');
            });

            closeProfile.addEventListener('click', function () {
                profileModal.classList.remove('active');
                mainContent.classList.remove('blurred');
            });
        });
        </script>
  <section class="showcase-area" id="showcase">
        <div class="showcase-container">
           <b> <h1 class="main-title" id="home">Eat Right Food</h1></b>
            <p>Eat Healty, it is good for our health.</p>
            <a href="#food" class="btn btn-primary">Menu</a>
        </div>
    </section>
    <section id="about">
        <div class="about-wrapper container">
            <div class="about-text">
                <p class="small">About Us</p>
                <h2>We've beem making healthy food last for 10 years</h2><br>
                <p>
                   At EAT RIGHT FOOD, we believe in creating more than just a dining experience; we strive to craft moments that linger in your memory. Established in 2070, our restaurant is a culinary haven where passion for food meets exceptional service.
                   Explore a diverse menu that combines traditional favorites with innovative twists. From classic comfort food to daring culinary creations, our menu is a testament to our commitment to culinary excellence.
                </p>
            </div>
            <div class="about-img">
                <img src="https://i.postimg.cc/mgpwzmx9/about-photo.jpg" alt="error" />
          </div>
        </div>
    </section>
    <section id="food">
       <B> <h2>Order food online now!</h2>
        <div class="food-container container">
            <div class="food-type fruite">
                <div class="img-container">


                
 <img src="https://img.freepik.com/free-photo/top-view-fast-food-mix-mozzarella-sticks-club-sandwich-hamburger-mushroom-pizza-caesar-shrimp-salad-french-fries-ketchup-mayo-cheese-sauces-table_141793-3998.jpg?t=st=1735317787~exp=1735321387~hmac=661c35c7de433b7efb8b58aba4fd859ba0cff622d288ecba6dbffc2b021163e2&w=740" alt="error" />
  <div class="img-content">
    <h3>Fast Food</h3>
       <a href="fast_food.php" class="btn btn-primary" target="blank">Order Here</a>
                    </div>
                </div>
            </div>
            <div class="food-type vegetable">
                <div class="img-container">
                    <img src="https://img.freepik.com/premium-photo/asian-assorted-food-set-dark-rustic-stone-background-chinese-dishes_92134-326.jpg?w=740" alt="error" />
                    <div class="img-content">
                        <h3>Chinese Food</h3>
                        <a href="chinese_food.php" class="btn btn-primary" target="blank">Order Here</a>
                    </div>
                </div>
            </div>
            <div class="food-type grin">
                <div class="img-container">
                    <img src="https://img.freepik.com/premium-photo/indian-hindu-veg-thali-food-platter-selective-focus_466689-35147.jpg?w=740" alt="error" />
                    <div class="img-content">
                        <h3>Nepali Food</h3>
                        <a href="nepali_food.php" class="btn btn-primary" target="blank">Order Here</a></div>
                    </div>

            </div>
        </div>
    </section>
    <section id="food-menu">
        <h2 class="food-menu-heading">Best-Selling Dishes at Our Restaurant</h2>
        <div class="food-menu-container container">
            <div class="food-menu-item">
                <div class="food-img">
                    <img src="https://images.pexels.com/photos/1049620/pexels-photo-1049620.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="error">
                </div>
                <div class="food-description">
                    <h2 class="food-titile">PIZZA</h2>
                    <p>
                      Pizza is a popular dish originating from Italy, consisting of a round, flat dough 
                      topped with tomato sauce, cheese, and various ingredients like meats, vegetables, 
                      and herbs.
                    </p><BR>
                    <div class="star-rating">
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                </div>
                </div>
            </div>

            <div class="food-menu-item">
                <div class="food-img">

                    <img src="https://images.pexels.com/photos/534285/pexels-photo-534285.jpeg" alt="error" />

                </div>

                <div class="food-description">
                    <h2 class="food-titile">BURGER</h2>
                    <p>
                        A burger is a sandwich consisting of a cooked ground meat patty, usually beef, 
                        placed inside a sliced bun. It is typically garnished with various toppings 
                        such as lettuce, tomato, onion, pickles, and condiments like ketchup and mayonnaise.
                    </p><BR>
                    <div class="star-rating">
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                </div>
                </div>
            </div>
            <div class="food-menu-item">
                <div class="food-img">

                    <img src="https://images.pexels.com/photos/1527603/pexels-photo-1527603.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="error" />
                </div>
                <div class="food-description">
                    <h2 class="food-titile">Pasta</h2>
                    <p>
                        Pasta is a versatile Italian dish made from durum wheat semolina or flour and water,
                         often with eggs. It comes in various shapes and sizes, such as spaghetti, penne, 
                         and lasagna.
                    </p><br>
                    <div class="star-rating">
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                </div>
                </div>
            </div>
            <div class="food-menu-item">
                <div class="food-img">
                    <img src="https://as1.ftcdn.net/v2/jpg/03/86/85/64/1000_F_386856433_UTNRrviaDxUsLJfEpVp24bdr3xqgQXjl.jpg" alt="error" />
                </div>
                <div class="food-description">
                    <h2 class="food-titile">Chow Mein</h2>
                    <p>
                    Chow mein is a Chinese stir-fried noodle dish that typically consists of wheat noodles,
                     vegetables, and proteins like chicken, beef, or shrimp. The ingredients are stir-fried 
                     in a wok with soy sauce and other seasonings.
                    </p><br>
                    <div class="star-rating">
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                </div>
                </div>
            </div>
            <div class="food-menu-item">
                <div class="food-img">
                    <img src="https://www.holidify.com/images/cmsuploads/compressed/Dal_Bhat_TarkariNepal_20190527155929.JPG" alt="error" />
                </div>
                <div class="food-description">
                    <h2 class="food-titile">Dal Bhat</h2>
                    <p>
                    Dal Bhat, Nepal's national dish, yet satisfying meal of steamed rice served with lentil soup, often accompanied by vegetables 
            and pickles.               

                    </p><br>
                    <div class="star-rating">
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                </div>
                </div>
            </div>
            <div class="food-menu-item">
                <div class="food-img">
                    <img src="https://res.cloudinary.com/rainforest-cruises/images/c_fill,g_auto/f_auto,q_auto/w_1120,h_732,c_fill,g_auto/v1661347465/india-food-aloo-chaat/india-food-aloo-chaat-1120x732.jpg" alt="error" />
                </div>
                <div class="food-description">
                    <h2 class="food-titile">Pakora</h2>
                    <p>
                    Pakora are a popular Indian snack consisting of vegetables or meat coated in a 
                    spiced chickpea flour batter and deep-fried to a golden crisp.
                    This savory treat offers a delightful crunch and a burst of flavorful spices. 
                </p><br>
                <div class="star-rating">
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span> 
                </div>
                </div>
            </div>
        </div>
    </section>
    <section id="testimonials">
        <h2 class="testimonial-title">What Our Customers Say</h2>

        <div class="testimonial-container container">
            <div class="testimonial-box">
                <div class="customer-detail">
                    <div class="customer-photo">
                        <img src="A.jpg" alt="" />
<p class="customer-name">Dinesh Bhusal </p>
                    </div>
                </div>
                <div class="star-rating">
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                </div>
                <p class="testimonial-text">
                    I'm impressed by your restaurant's exceptional service and the diverse, 
                    delicious menu.
                     Your commitment to quality is truly commendable!"
                </p>

            </div>
            <div class="testimonial-box">
                <div class="customer-detail">
                    <div class="customer-photo">
                        <img src="B.jpg" alt="" />
<p class="customer-name">Suman Bhusal</p>
                    </div>
                </div>
                <div class="star-rating">
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                </div>
                <p class="testimonial-text">
                     I want to commend you for the outstanding service and delightful variety of dishes 
                     at your restaurant. Your commitment to excellence is evident.
                </p>

            </div>
            <div class="testimonial-box">
                <div class="customer-detail">
                    <div class="customer-photo">
                        <img src="c.jpg" alt="" />
                        <p class="customer-name">Kamal Nyaupane</p>
                    </div>
                </div>
                <div class="star-rating">
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                </div>
                <p class="testimonial-text">
                The service was exceptional, the food was delicious, and the atmosphere was perfect.
                 Definitely recommend this place for a great dining experience!
                </p>
            </div>
        </div>
    </section>
    <section id="contact">
        <div class="contact-container container">
            <div class="contact-img">
                <img src="https://images.pexels.com/photos/958545/pexels-photo-958545.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="error">
            </div>
            <div class="form-container">
                <h2>Contact Us</h2>
                <input type="text" placeholder="Your Name" />
                <input type="email" placeholder="E-Mail" />
                <textarea cols="30" rows="6" placeholder="Type Your Message"></textarea>
                <a href="#" class="btn btn-primary">Submit</a>
            </div>
        </div>
    </section>
    <footer id="footer" style="background-color: #000000; color: #ffffff; padding: 60px 20px; text-align: center; position: relative; border-top: 2px solid #444444;">
    <p style="margin: 0 0 30px; font-size: 16px; color: #aaaaaa;">Serving Quality Food Since 1995</p>

   
    <div style="margin-bottom: 30px; display: flex; justify-content: center; gap: 15px;">
       
        <a href="https://facebook.com" target="_blank" style="text-decoration: none; display: inline-block; width: 50px; height: 50px; border-radius: 50%; background-color: #444444; display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
            <img src="https://img.icons8.com/ios-filled/24/ffffff/facebook--v1.png" alt="Facebook">
        </a>
     
        <a href="https://twitter.com" target="_blank" style="text-decoration: none; display: inline-block; width: 50px; height: 50px; border-radius: 50%; background-color: #444444; display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
            <img src="https://img.icons8.com/ios-filled/24/ffffff/twitter--v1.png" alt="Twitter">
        </a>
      
        <a href="https://instagram.com" target="_blank" style="text-decoration: none; display: inline-block; width: 50px; height: 50px; border-radius: 50%; background-color: #444444; display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
            <img src="https://img.icons8.com/ios-filled/24/ffffff/instagram-new.png" alt="Instagram">
        </a>

        <a href="https://linkedin.com" target="_blank" style="text-decoration: none; display: inline-block; width: 50px; height: 50px; border-radius: 50%; background-color: #444444; display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
            <img src="https://img.icons8.com/ios-filled/24/ffffff/linkedin-circled--v1.png" alt="LinkedIn">
        </a>
     
        <a href="https://youtube.com" target="_blank" style="text-decoration: none; display: inline-block; width: 50px; height: 50px; border-radius: 50%; background-color: #444444; display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
            <img src="https://img.icons8.com/ios-filled/24/ffffff/youtube-play.png" alt="YouTube">
        </a>
   
        <a href="mailto:contact@restaurant.com" style="text-decoration: none; display: inline-block; width: 50px; height: 50px; border-radius: 50%; background-color: #444444; display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
            <img src="https://img.icons8.com/ios-filled/24/ffffff/new-post.png" alt="Email">
        </a>
    </div>

    
    <a href="terms.html" style="display: inline-block; padding: 12px 25px; background-color: #1abc9c; color: #ffffff; border-radius: 25px; text-decoration: none; font-size: 14px; transition: all 0.3s;"
       onmouseover="this.style.backgroundColor='#16a085'; this.style.transform='translateY(-3px)';"
       onmouseout="this.style.backgroundColor='#1abc9c'; this.style.transform='translateY(0)';">
        Terms and Conditions
    </a>
    <div style="height: 2px; background: linear-gradient(to right, #1abc9c, #3498db); margin: 30px auto; width: 80%;"></div>

    
    <a href="#top" style="display: inline-block; color: #ffffff; text-decoration: none; font-size: 14px; margin-top: 20px;">Back to Top ↑</a><br><Br><br>
    <h2 style="margin: 0; font-size: 16px;">Restaurant &copy; 2025 All Rights Reserved</h2>
</footer>


    <script>
        // Adding hover effects dynamically for social icons
        const icons = document.querySelectorAll('#footer a');
        icons.forEach(icon => {
            icon.addEventListener('mouseover', () => {
                icon.style.backgroundColor = '#1abc9c';
                icon.style.transform = 'scale(1.2)';
            });
            icon.addEventListener('mouseout', () => {
                icon.style.backgroundColor = '#444444';
                icon.style.transform = 'scale(1)';
            });
        });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="app.js"></script>


</body>

</html>
