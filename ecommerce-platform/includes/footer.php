<footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container">
        <div class="row">
            <!-- Quick Links -->
            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase mb-4">Shop</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/products.php" class="text-white-50">All Products</a></li>
                    <li class="mb-2"><a href="/new-arrivals.php" class="text-white-50">New Arrivals</a></li>
                    <li class="mb-2"><a href="/deals.php" class="text-white-50">Special Offers</a></li>
                    <li class="mb-2"><a href="/gift-cards.php" class="text-white-50">Gift Cards</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase mb-4">Help</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/contact.php" class="text-white-50">Contact Us</a></li>
                    <li class="mb-2"><a href="/faq.php" class="text-white-50">FAQs</a></li>
                    <li class="mb-2"><a href="/shipping.php" class="text-white-50">Shipping Info</a></li>
                    <li class="mb-2"><a href="/returns.php" class="text-white-50">Returns Policy</a></li>
                </ul>
            </div>

            <!-- Company Info -->
            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase mb-4">Company</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/about.php" class="text-white-50">About Us</a></li>
                    <li class="mb-2"><a href="/blog.php" class="text-white-50">Blog</a></li>
                    <li class="mb-2"><a href="/careers.php" class="text-white-50">Careers</a></li>
                    <li class="mb-2"><a href="/privacy.php" class="text-white-50">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase mb-4">Stay Connected</h5>
                <form class="mb-3">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your email" required>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
                <div class="social-icons">
                    <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>

        <hr class="mb-4" style="border-color: rgba(255,255,255,0.1);">

        <!-- Copyright + Payment Methods -->
        <div class="row align-items-center">
            <div class="col-md-6 text-md-start mb-3 mb-md-0">
                &copy; <?php echo date("Y"); ?> Nozama. All rights reserved.
            </div>
            <div class="col-md-6 text-md-end">
                <img src="/assets/img/payment-methods.png" alt="Payment Methods" class="img-fluid" style="max-height: 30px;">
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<a href="#" class="btn btn-primary position-fixed bottom-0 end-0 m-4 rounded-circle shadow" id="backToTop" style="width: 50px; height: 50px; display: none;">
    <i class="fas fa-arrow-up"></i>
</a>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/script.js"></script>
<script>
// Back to top button
window.addEventListener('scroll', function() {
    var backToTop = document.getElementById('backToTop');
    if (window.pageYOffset > 300) {
        backToTop.style.display = 'flex';
    } else {
        backToTop.style.display = 'none';
    }
});

document.getElementById('backToTop').addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({top: 0, behavior: 'smooth'});
});
</script>
</body>
</html>