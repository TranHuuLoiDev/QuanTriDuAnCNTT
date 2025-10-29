<?php 
session_start();

if (!isset($_GET['key']) || empty($_GET['key'])) {
	header("Location: index.php");
	exit;
}
$key = $_GET['key'];

include "db_conn.php";
include "php/func-book.php";
$books = search_books($conn, $key);

include "php/func-author.php";
$authors = get_all_author($conn);

include "php/func-category.php";
$categories = get_all_categories($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Search Results</title>

	<!-- Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

	<style>
		body {
			font-family: 'Nunito', sans-serif;
			background: #f4f7fc;
			color: #333;
		}
		.navbar {
			background: linear-gradient(90deg, #007bff, #00c6ff);
			box-shadow: 0 3px 10px rgba(0,0,0,0.1);
		}
		.navbar-brand, .nav-link {
			color: white !important;
			font-weight: 600;
		}
		.nav-link:hover {
			text-decoration: underline;
		}
		.search-bar {
			width: 400px;
		}
		.search-bar input {
			border-radius: 50px;
			padding: 6px 20px;
			border: none;
		}
		h2 {
			margin-top: 30px;
			font-weight: 700;
			color: #007bff;
		}
		.card {
			width: 18rem;
			border: none;
			border-radius: 15px;
			overflow: hidden;
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
			transition: all 0.3s ease-in-out;
			background: white;
		}
		.card:hover {
			transform: translateY(-5px);
			box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
		}
		.card-img-top {
			height: 250px;
			object-fit: cover;
		}
		.card-title {
			color: #007bff;
			font-weight: 600;
		}
		.card-text {
			font-size: 0.9rem;
			color: #555;
		}
		.btn {
			border-radius: 10px;
			font-weight: 600;
			margin-right: 5px;
		}
		.btn-success {
			background-color: #00c851;
			border: none;
		}
		.btn-primary {
			background-color: #007bff;
			border: none;
		}
		footer {
			text-align: center;
			padding: 20px;
			margin-top: 50px;
			background: #fff;
			border-top: 1px solid #ddd;
			color: #555;
			font-weight: 500;
		}
		footer span {
			color: #007bff;
		}
		.alert img {
			margin-bottom: 10px;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg">
	  <div class="container-fluid">
	    <a class="navbar-brand" href="index.php">📚 ABL</a>
	    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
	      <span class="navbar-toggler-icon"></span>
	    </button>
	    <div class="collapse navbar-collapse" id="navbarNav">
	      <ul class="navbar-nav ms-auto me-3">
	        <li class="nav-item"><a class="nav-link" href="index.php">Store</a></li>
	        <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
	        <li class="nav-item"><a class="nav-link" href="#">About</a></li>
	        <li class="nav-item">
	        	<?php if (isset($_SESSION['user_id'])) { ?>
	        		<a class="nav-link" href="admin.php">Admin</a>
	        	<?php } else { ?>
	        		<a class="nav-link" href="login.php">Login</a>
	        	<?php } ?>
	        </li>
	      </ul>
	      <!-- Search bar -->
	      <form action="search.php" method="get" class="d-flex search-bar">
	      	<input type="text" class="form-control" name="key" placeholder="Search books..." value="<?=htmlspecialchars($key)?>">
	      </form>
	    </div>
	  </div>
	</nav>

	<div class="container mt-4">
		<h2>Search results for "<b><?=$key?></b>"</h2>

		<div class="mt-4 d-flex flex-wrap justify-content-start gap-3">
			<?php if ($books == 0){ ?>
				<div class="alert alert-warning text-center p-5 w-100" role="alert">
        	     <img src="img/empty-search.png" width="100">
        	     <br>
				 The key <b>"<?=$key?>"</b> didn't match any record in the database.
				</div>
			<?php } else { ?>
				<?php foreach ($books as $book) { ?>
					<div class="card">
						<img src="uploads/cover/<?=$book['cover']?>" class="card-img-top" alt="<?=$book['title']?>">
						<div class="card-body">
							<h5 class="card-title"><?=$book['title']?></h5>
							<p class="card-text">
								<b>By:</b>
								<?php foreach($authors as $author){ 
									if ($author['id'] == $book['author_id']) {
										echo $author['name'];
										break;
									}
								} ?>
								<br><small><?=$book['description']?></small>
								<br><b>Category:</b>
								<?php foreach($categories as $category){ 
									if ($category['id'] == $book['category_id']) {
										echo $category['name'];
										break;
									}
								} ?>
							</p>
							<a href="uploads/files/<?=$book['file']?>" class="btn btn-success">Open</a>
							<a href="uploads/files/<?=$book['file']?>" class="btn btn-primary" download="<?=$book['title']?>">Download</a>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>

	<footer>
		© 2025 ABL — Designed with <span>💙</span>
	</footer>
</body>
</html>
