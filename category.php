<?php 
session_start();

if (!isset($_GET['id'])) {
	header("Location: index.php");
	exit;
}

$id = $_GET['id'];

include "db_conn.php";
include "php/func-book.php";
$books = get_books_by_category($conn, $id);

include "php/func-author.php";
$authors = get_all_author($conn);

include "php/func-category.php";
$categories = get_all_categories($conn);
$current_category = get_category($conn, $id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$current_category['name']?></title>

	<!-- Bootstrap 5 -->
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
		h1.display-4 {
			color: #007bff;
			margin-top: 30px;
			margin-bottom: 20px;
			font-weight: 700;
			text-transform: uppercase;
		}
		.pdf-list {
			display: flex;
			flex-wrap: wrap;
			gap: 1.5rem;
			justify-content: start;
			margin-top: 1rem;
			flex: 1;
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
		.card-body {
			padding: 15px;
		}
		.card-title {
			color: #007bff;
			font-size: 1.1rem;
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
		.category {
			width: 300px;
			margin-left: 30px;
		}
		.list-group-item.active {
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
		.container-flex {
			display: flex;
			align-items: flex-start;
			gap: 20px;
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
	      <ul class="navbar-nav ms-auto">
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
	    </div>
	  </div>
	</nav>

	<div class="container mt-4">
		<h1 class="display-4 fs-3 d-flex align-items-center gap-2">
			<a href="index.php" class="text-decoration-none text-secondary">
				<img src="img/back-arrow.PNG" width="35" alt="back">
			</a>
			<?=$current_category['name']?>
		</h1>

		<div class="container-flex mt-4">
			<div class="pdf-list">
				<?php if ($books == 0) { ?>
					<div class="alert alert-warning text-center p-5 w-100" role="alert">
						<img src="img/empty.png" width="100">
						<br>There is no book in the database.
					</div>
				<?php } else { ?>
					<?php foreach ($books as $book) { ?>
						<div class="card">
							<img src="uploads/cover/<?=$book['cover']?>" class="card-img-top" alt="<?=$book['title']?>">
							<div class="card-body">
								<h5 class="card-title"><?=$book['title']?></h5>
								<p class="card-text">
									<b>By:</b> 
									<?php 
									foreach($authors as $author){ 
										if ($author['id'] == $book['author_id']) {
											echo $author['name'];
											break;
										}
									}
									?>
									<br><small><?=$book['description']?></small>
									<br><b>Category:</b>
									<?php 
									foreach($categories as $category){ 
										if ($category['id'] == $book['category_id']) {
											echo $category['name'];
											break;
										}
									}
									?>
								</p>
								<a href="uploads/files/<?=$book['file']?>" class="btn btn-success">Open</a>
								<a href="uploads/files/<?=$book['file']?>" class="btn btn-primary" download="<?=$book['title']?>">Download</a>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
			</div>

			<div class="category">
				<div class="list-group mb-4">
					<a href="#" class="list-group-item list-group-item-action active">Category</a>
					<?php if ($categories != 0) {
						foreach ($categories as $category ) { ?>
						<a href="category.php?id=<?=$category['id']?>" class="list-group-item list-group-item-action"><?=$category['name']?></a>
					<?php } } ?>
				</div>

				<div class="list-group">
					<a href="#" class="list-group-item list-group-item-action active">Author</a>
					<?php if ($authors != 0) {
						foreach ($authors as $author ) { ?>
						<a href="author.php?id=<?=$author['id']?>" class="list-group-item list-group-item-action"><?=$author['name']?></a>
					<?php } } ?>
				</div>
			</div>
		</div>
	</div>

	<footer>
		© 2025 ATL — Designed with <span>💙</span>
	</footer>
</body>
</html>
