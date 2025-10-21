<?php  
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {

	include "db_conn.php";
	include "php/func-book.php";
	$books = get_all_books($conn);
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
	<title>Admin Dashboard</title>

	<!-- Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

	<style>
		body {
			background: linear-gradient(135deg, #74ABE2, #5563DE);
			font-family: 'Poppins', sans-serif;
			min-height: 100vh;
		}
		.navbar {
			background: rgba(255, 255, 255, 0.9);
			box-shadow: 0 2px 8px rgba(0,0,0,0.1);
			border-radius: 10px;
			margin-top: 10px;
		}
		.navbar-brand {
			font-weight: bold;
			color: #3347B0 !important;
		}
		.nav-link {
			color: #333 !important;
			font-weight: 500;
			transition: 0.3s;
		}
		.nav-link:hover {
			color: #007bff !important;
			transform: translateY(-2px);
		}
		.section-card {
			background: #fff;
			border-radius: 15px;
			padding: 25px;
			margin-top: 30px;
			box-shadow: 0 4px 12px rgba(0,0,0,0.2);
			color: #333;
		}
		h4 {
			color: #3347B0;
			font-weight: 600;
			margin-bottom: 20px;
		}
		.table thead {
			background: #3347B0;
			color: white;
			border-radius: 10px;
		}
		.btn-warning {
			background: #ffc107;
			border: none;
		}
		.btn-danger {
			background: #dc3545;
			border: none;
		}
		.btn-success {
			background: #198754;
			border: none;
		}
		.search-box {
			max-width: 400px;
			margin: 20px auto;
		}
		.alert {
			border-radius: 10px;
			font-weight: 500;
		}
		footer {
			text-align: center;
			margin-top: 50px;
			color: #fff;
			font-weight: 500;
			padding: 15px 0;
		}
	</style>
</head>

<body>
	<div class="container">
		<!-- NAVBAR -->
		<nav class="navbar navbar-expand-lg shadow-sm">
			<div class="container-fluid">
				<a class="navbar-brand fw-bold" href="admin.php"><i class="bi bi-speedometer2 me-2"></i>Admin Panel</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav ms-auto">
						<li class="nav-item"><a class="nav-link" href="index.php">Store</a></li>
						<li class="nav-item"><a class="nav-link" href="add-book.php">Add Book</a></li>
						<li class="nav-item"><a class="nav-link" href="add-category.php">Add Category</a></li>
						<li class="nav-item"><a class="nav-link" href="add-author.php">Add Author</a></li>
						<li class="nav-item"><a class="nav-link text-danger fw-bold" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
					</ul>
				</div>
			</div>
		</nav>

		<!-- SEARCH BAR -->
		<form action="search.php" method="get" class="search-box">
			<div class="input-group shadow-sm">
				<input type="text" class="form-control" name="key" placeholder="🔍 Search Book...">
				<button class="btn btn-primary"><i class="bi bi-search"></i></button>
			</div>
		</form>

		<!-- ALERT -->
		<?php if (isset($_GET['error'])) { ?>
			<div class="alert alert-danger"><?=htmlspecialchars($_GET['error']); ?></div>
		<?php } ?>
		<?php if (isset($_GET['success'])) { ?>
			<div class="alert alert-success"><?=htmlspecialchars($_GET['success']); ?></div>
		<?php } ?>

		<!-- BOOK TABLE -->
		<div class="section-card">
			<h4><i class="bi bi-book me-2"></i>All Books</h4>
			<?php if ($books == 0) { ?>
				<div class="alert alert-warning text-center p-5">
					<img src="img/empty.png" width="100"><br>No books found
				</div>
			<?php } else { ?>
				<div class="table-responsive">
					<table class="table table-bordered table-hover align-middle">
						<thead>
							<tr>
								<th>#</th>
								<th>Title</th>
								<th>Author</th>
								<th>Description</th>
								<th>Category</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $i=0; foreach ($books as $book) { $i++; ?>
								<tr>
									<td><?=$i?></td>
									<td>
										<img width="80" src="uploads/cover/<?=$book['cover']?>" class="rounded shadow-sm mb-1">
										<div><a href="uploads/files/<?=$book['file']?>" class="fw-bold text-decoration-none"><?=$book['title']?></a></div>
									</td>
									<td>
										<?php foreach ($authors as $author) {
											if ($author['id'] == $book['author_id']) echo $author['name'];
										} ?>
									</td>
									<td><?=htmlspecialchars($book['description'])?></td>
									<td>
										<?php foreach ($categories as $category) {
											if ($category['id'] == $book['category_id']) echo $category['name'];
										} ?>
									</td>
									<td>
										<a href="edit-book.php?id=<?=$book['id']?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
										<a href="php/delete-book.php?id=<?=$book['id']?>" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php } ?>
		</div>

		<!-- CATEGORY TABLE -->
		<div class="section-card">
			<h4><i class="bi bi-tags me-2"></i>All Categories</h4>
			<?php if ($categories == 0) { ?>
				<div class="alert alert-warning text-center p-5">
					<img src="img/empty.png" width="100"><br>No categories found
				</div>
			<?php } else { ?>
				<table class="table table-bordered table-hover align-middle">
					<thead>
						<tr>
							<th>#</th>
							<th>Category Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $j=0; foreach ($categories as $category) { $j++; ?>
						<tr>
							<td><?=$j?></td>
							<td><?=$category['name']?></td>
							<td>
								<a href="edit-category.php?id=<?=$category['id']?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
								<a href="php/delete-category.php?id=<?=$category['id']?>" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>
		</div>

		<!-- AUTHOR TABLE -->
		<div class="section-card mb-5">
			<h4><i class="bi bi-person-lines-fill me-2"></i>All Authors</h4>
			<?php if ($authors == 0) { ?>
				<div class="alert alert-warning text-center p-5">
					<img src="img/empty.png" width="100"><br>No authors found
				</div>
			<?php } else { ?>
				<table class="table table-bordered table-hover align-middle">
					<thead>
						<tr>
							<th>#</th>
							<th>Author Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $k=0; foreach ($authors as $author) { $k++; ?>
						<tr>
							<td><?=$k?></td>
							<td><?=$author['name']?></td>
							<td>
								<a href="edit-author.php?id=<?=$author['id']?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
								<a href="php/delete-author.php?id=<?=$author['id']?>" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>
		</div>

		<footer>© 2025 Online Book Store — Designed with 💙</footer>
	</div>
</body>
</html>

<?php } else {
  header("Location: login.php");
  exit;
} ?>
