
<?php include 'backend/views/header.php'; ?>

<!-- Homepage content goes here -->
<section class="hero" id="home">
	<div class="container">
		<div class="hero-content">
			<h1>Welcome to NETH Bookhive</h1>
			<p>🧠 Transform Your Mind • 💰 Master Your Money • 🚀 Achieve Your Goals</p>
			<p>Discover life-changing psychology and finance books from bestselling authors</p>
			<div class="hero-buttons">
				<button class="cta-btn primary" onclick="document.querySelector('.featured-psychology-section').scrollIntoView()">
					<i class="fas fa-brain"></i> Psychology Books
				</button>
				<button class="cta-btn secondary" onclick="document.getElementById('books').scrollIntoView()">
					<i class="fas fa-book"></i> All Books
				</button>
			</div>
		</div>
	</div>
</section>

<!-- Add more homepage sections as needed -->

<!-- Featured Books Section -->
<section class="featured-books-section">
	<div class="container">
		<h2>Featured Books</h2>
		<div class="books-grid">
		<?php
		// Fetch a few featured books from database
		include_once 'backend/config/database.php';
		include_once 'backend/models/Book.php';
		$database = new Database();
		$db = $database->getConnection();
		$bookModel = new Book($db);
		$stmt = $bookModel->read();
		$count = 0;
		while (($book = $stmt->fetch(PDO::FETCH_ASSOC)) && $count < 4) {
			include 'backend/views/book_card.php';
			$count++;
		}
		?>
		</div>
	</div>
</section>

<?php include 'backend/views/footer.php'; ?>
