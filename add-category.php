<?php  
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Add Category</title>

	<!-- Bootstrap 5 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

	<style>
		body {
			background: linear-gradient(135deg, #74ABE2, #5563DE);
			font-family: 'Poppins', sans-serif;
			min-height: 100vh;
			display: flex;
			flex-direction: column;
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
		.form-container {
			background: #fff;
			border-radius: 20px;
			box-shadow: 0 6px 20px rgba(0,0,0,0.2);
			padding: 40px;
			max-width: 600px;
			margin: 60px auto;
			animation: fadeIn 0.8s ease-in-out;
		}
		h1 {
			color: #3347B0;
			font-weight: 600;
		}
		label {
			font-weight: 500;
		}
		.btn-primary {
			background-color: #3347B0;
			border: none;
			font-weight: 500;
			padding: 10px 20px;
			border-radius: 10px;
			transition: 0.3s;
		}
		.btn-primary:hover {
			background-color: #25338F;
			transform: scale(1.05);
		}
		footer {
			text-align: center;
			margin-top: auto;
			padding: 15px 0;
			color: #fff;
			font-weight: 500;
		}
		.alert {
			border-radius: 10px;
			font-weight: 500;
		}
		@keyframes fadeIn {
			from {opacity: 0; transform: translateY(20px);}
			to {opacity: 1; transform: translateY(0);}
		}
	</style>
</head>

<body>
	<div class="container">
		<!-- Navbar -->
		<nav class="navbar navbar-expand-lg navbar-light">
			<div class="container-fluid">
				<a class="navbar-brand" href="admin.php"><i class="bi bi-speedometer2 me-2"></i>Admin</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav ms-auto">
						<li class="nav-item"><a class="nav-link" href="index.php">Store</a></li>
						<li class="nav-item"><a class="nav-link" href="add-book.php">Add Book</a></li>
						<li class="nav-item"><a class="nav-link active" href="add-category.php">Add Category</a></li>
						<li class="nav-item"><a class="nav-link" href="add-author.php">Add Author</a></li>
						<li class="nav-item"><a class="nav-link text-danger fw-bold" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
					</ul>
				</div>
			</div>
		</nav>

		<!-- Form -->
		<div class="form-container shadow-lg">
			<h1 class="text-center mb-4">Add New Category</h1>

			<?php if (isset($_GET['error'])) { ?>
				<div class="alert alert-danger"><?=htmlspecialchars($_GET['error']); ?></div>
			<?php } ?>
			<?php if (isset($_GET['success'])) { ?>
				<div class="alert alert-success"><?=htmlspecialchars($_GET['success']); ?></div>
			<?php } ?>

			<form action="php/add-category.php" method="post">
				<div class="mb-3">
					<label for="category_name" class="form-label">Category Name</label>
					<input type="text" class="form-control form-control-lg" id="category_name" name="category_name" placeholder="Enter category name...">
				</div>

				<div class="d-grid mt-4">
					<button type="submit" class="btn btn-primary btn-lg">
						<i class="bi bi-plus-circle me-2"></i>Add Category
					</button>
				</div>
			</form>
		</div>

		<footer>© 2025 ABL — Designed with 💙</footer>
	</div>
</body>
</html>

<?php } else {
  header("Location: login.php");
  exit;
} ?>
