<?php 
session_start();

include "db_conn.php";
include "php/func-book.php";
include "php/func-author.php";
include "php/func-category.php";

$books = get_all_books($conn);
$authors = get_all_author($conn);
$categories = get_all_categories($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Book Store</title>

	<!-- Bootstrap 5 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

	<style>
	body {
		font-family: 'Poppins', sans-serif;
		background: linear-gradient(135deg, #e0f7fa, #f8bbd0, #e1bee7);
		color: #333;
		display: flex;
		flex-direction: column;
		min-height: 100vh;
	}

	html, body {
		height: 100%;
	}

	.container {
		flex: 1;
	}

	.navbar {
		background: linear-gradient(90deg, #007bff, #6610f2);
		padding: 0.8rem 1rem;
		box-shadow: 0 4px 15px rgba(0,0,0,0.1);
	}
	.navbar-brand {
		font-weight: 700;
		color: #fff !important;
		font-size: 1.3rem;
		text-transform: uppercase;
		letter-spacing: 1px;
	}
	.nav-link {
		color: #f8f9fa !important;
		font-weight: 500;
		margin: 0 0.3rem;
	}
	.nav-link:hover {
		color: #ffd54f !important;
	}
	.navbar .form-control {
		border-radius: 25px;
		border: none;
		padding: 0.5rem 1rem;
		box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
	}
	.navbar .btn-search {
		border-radius: 25px;
		background-color: #ffd54f;
		color: #333;
		font-weight: 600;
		padding: 0.5rem 1.2rem;
		border: none;
		transition: 0.3s;
	}
	.navbar .btn-search:hover {
		background-color: #ffca28;
		transform: scale(1.05);
	}
	.pdf-list {
		flex: 3;
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
		gap: 1.5rem;
		padding-right: 2rem;
	}
	.category {
		flex: 1;
		min-width: 240px;
	}
	.card {
		border: none;
		border-radius: 18px;
		background: linear-gradient(135deg, #ffffff, #f3e5f5);
		box-shadow: 0 4px 10px rgba(0,0,0,0.1);
		transition: all 0.3s ease;
	}
	.card:hover {
		transform: translateY(-6px);
		box-shadow: 0 8px 20px rgba(0,0,0,0.2);
	}
	.card img {
		height: 250px;
		object-fit: cover;
		border-top-left-radius: 18px;
		border-top-right-radius: 18px;
	}
	.card-title {
		font-size: 1.1rem;
		font-weight: 600;
		color: #4a148c;
	}
	.card-text {
		font-size: 0.9rem;
		color: #555;
	}
	.card .btn-success {
		background-color: #4caf50;
		border: none;
		width: 100%;
		margin-top: 5px;
		border-radius: 10px;
	}
	.card .btn-primary {
		background-color: #2196f3;
		border: none;
		width: 100%;
		margin-top: 5px;
		border-radius: 10px;
	}
	.list-group-item.active {
		background: linear-gradient(90deg, #2196f3, #00bcd4);
		border: none;
	}
	.list-group-item {
		border: none;
		border-bottom: 1px solid #eee;
		color: #333;
		font-weight: 500;
	}
	.list-group-item:hover {
		background-color: #f1f8e9;
		color: #007bff;
	}
	.alert {
		background-color: #fff3cd;
		border: none;
		color: #856404;
		font-weight: 500;
	}
	footer {
		background: linear-gradient(90deg, #007bff, #6610f2);
		color: white;
		text-align: center;
		padding: 1rem 0;
		border-top-left-radius: 15px;
		border-top-right-radius: 15px;
		font-weight: 500;
		box-shadow: 0 -3px 10px rgba(0,0,0,0.1);
		margin-top: auto;
	}
</style>
</head>
<body>

<!-- Navbar with Search -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="index.php">Online Book Store</a>
    <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon bg-light rounded"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item"><a class="nav-link active" href="index.php">Store</a></li>
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
      <form class="d-flex" action="search.php" method="get">
        <input class="form-control me-2" type="search" name="key" placeholder="Search Book...">
        <button class="btn-search" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

<div class="container py-5">
	<div class="d-flex flex-wrap-reverse">
		<div class="pdf-list">
			<?php if ($books == 0){ ?>
				<div class="alert text-center p-5 w-100">
					<img src="img/empty.png" width="100"><br>
					There is no book in the database
				</div>
			<?php } else { 
				foreach ($books as $book) { ?>
					<div class="card">
						<img src="uploads/cover/<?=$book['cover']?>" alt="<?=$book['title']?>">
						<div class="card-body">
							<h5 class="card-title"><?=$book['title']?></h5>
							<p class="card-text">
								<i><b>By:
									<?php foreach($authors as $author){ 
										if ($author['id'] == $book['author_id']) {
											echo $author['name'];
											break;
										}
									} ?>
								</b></i><br>
								<?=$book['description']?><br>
								<i><b>Category:
									<?php foreach($categories as $category){ 
										if ($category['id'] == $book['category_id']) {
											echo $category['name'];
											break;
										}
									} ?>
								</b></i>
							</p>
							<a href="uploads/files/<?=$book['file']?>" class="btn btn-success">Open</a>
							<a href="uploads/files/<?=$book['file']?>" class="btn btn-primary" download="<?=$book['title']?>">Download</a>
						</div>
					</div>
			<?php } } ?>
		</div>

		<div class="category mt-4 mt-lg-0">
			<div class="list-group mb-4">
				<?php if ($categories != 0){ ?>
					<a href="#" class="list-group-item list-group-item-action active">Category</a>
					<?php foreach ($categories as $category){ ?>
						<a href="category.php?id=<?=$category['id']?>" class="list-group-item list-group-item-action"><?=$category['name']?></a>
					<?php } } ?>
			</div>

			<div class="list-group">
				<?php if ($authors != 0){ ?>
					<a href="#" class="list-group-item list-group-item-action active">Author</a>
					<?php foreach ($authors as $author){ ?>
						<a href="author.php?id=<?=$author['id']?>" class="list-group-item list-group-item-action"><?=$author['name']?></a>
					<?php } } ?>
			</div>
		</div>
	</div>
</div>

<footer>
	© 2025 Online Book Store — Designed with 💙
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
